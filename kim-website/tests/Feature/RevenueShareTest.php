<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\DigitalProduct;
use App\Models\DigitalOrder;
use App\Models\DigitalOrderItem;
use App\Models\RevenueShare;
use App\Models\InstructorEarning;

class RevenueShareTest extends TestCase
{
    use RefreshDatabase;

    public function test_edutech_payment_creates_revenue_share_and_earnings()
    {
        $instructor = User::factory()->create();
        $student = User::factory()->create();

        $course = Course::create([
            'instructor_id' => $instructor->id,
            'title' => 'Test Course',
            'slug' => 'test-course',
            'description' => 'A test course',
            'price' => 100000,
            'is_published' => true,
        ]);

        $enrollment = Enrollment::create([
            'course_id' => $course->id,
            'student_id' => $student->id,
            'payment_amount' => 100000,
            'status' => 'pending',
        ]);

        $payment = Payment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'enrollment_id' => $enrollment->id,
            'transaction_id' => 'TRX-EDU-1',
            'amount' => 100000,
            'status' => 'pending',
        ]);

        $this->assertDatabaseCount('revenue_shares', 0);

        // Mark paid - should dispatch event and create revenue share
        $payment->markAsPaid();

        $this->assertDatabaseHas('revenue_shares', [
            'transaction_code' => 'TRX-EDU-1',
            'status' => 'completed',
        ]);

        $revenue = RevenueShare::where('transaction_code', 'TRX-EDU-1')->first();
        $this->assertNotNull($revenue);
        $this->assertEquals('edutech', $revenue->course_type);

        // Instructor earning should be created
        $earning = InstructorEarning::where('instructor_id', $instructor->id)->first();
        $this->assertNotNull($earning);
        $this->assertGreaterThan(0, $earning->available_balance);
    }

    public function test_digital_order_creates_revenue_share_company_only()
    {
        $category = \App\Models\DigitalProductCategory::create([
            'name' => 'Books',
            'slug' => 'books',
            'description' => 'Books category',
            'is_active' => true,
        ]);

        $product = DigitalProduct::create([
            'category_id' => $category->id,
            'name' => 'Ebook',
            'slug' => 'ebook',
            'price' => 50000,
            'type' => 'ebook',
            'is_active' => true,
        ]);

        $order = DigitalOrder::create([
            'order_number' => 'DIG-1',
            'customer_email' => 'buyer@example.com',
            'subtotal' => 50000,
            'tax' => 0,
            'total' => 50000,
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        DigitalOrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_type' => $product->type,
            'price' => $product->price,
            'quantity' => 1,
            'subtotal' => $product->price,
        ]);

        $order->markAsPaid();

        $this->assertDatabaseHas('revenue_shares', [
            'transaction_code' => 'DIG-1',
            'status' => 'completed',
            'course_type' => 'kim_digital',
        ]);

        $revenue = RevenueShare::where('transaction_code', 'DIG-1')->first();
        $this->assertNotNull($revenue);
        // since no instructor, instructor share should be 0
        $this->assertEquals(0.00, (float) $revenue->instructor_share);
        $this->assertEquals(50000.00, (float) $revenue->company_share);
    }
}
