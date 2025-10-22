<?php

namespace Database\Seeders;

use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            return;
        }

        $transactionTypes = ['deposit', 'withdrawal', 'payment', 'refund', 'commission', 'payout'];
        $paymentMethods = ['mpesa', 'paypal', 'bank_transfer', 'credit_card'];
        $statuses = ['pending', 'completed', 'failed', 'cancelled'];

        foreach ($users as $user) {
            // Create wallet for each user
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'balance' => fake()->randomFloat(2, 0, 1000),
                'currency' => 'USD',
                'is_active' => true,
                'last_transaction_at' => now()->subDays(rand(0, 30)),
            ]);

            // Create transaction history
            $transactionCount = rand(3, 15);
            $runningBalance = 0;

            for ($i = 0; $i < $transactionCount; $i++) {
                $type = fake()->randomElement($transactionTypes);
                $amount = fake()->randomFloat(2, 10, 500);
                
                // Adjust balance based on transaction type
                if (in_array($type, ['deposit', 'refund'])) {
                    $runningBalance += $amount;
                } elseif (in_array($type, ['withdrawal', 'payment', 'payout'])) {
                    $amount = min($amount, $runningBalance + 100); // Ensure we don't go too negative
                    $runningBalance -= $amount;
                } elseif ($type === 'commission') {
                    $amount = fake()->randomFloat(2, 5, 50);
                    $runningBalance += $amount;
                }

                Transaction::create([
                    'user_id' => $user->id,
                    'wallet_id' => $wallet->id,
                    'type' => $type,
                    'amount' => $amount,
                    'balance_after' => $runningBalance,
                    'currency' => 'USD',
                    'status' => fake()->randomElement($statuses, [80, 10, 5, 5]), // 80% completed
                    'payment_method' => fake()->randomElement($paymentMethods),
                    'reference_id' => 'TXN_' . fake()->unique()->numerify('##########'),
                    'description' => $this->getTransactionDescription($type),
                    'metadata' => [
                        'ip_address' => fake()->ipv4(),
                        'user_agent' => fake()->userAgent(),
                        'location' => fake()->city(),
                    ],
                    'processed_at' => fake()->dateTimeBetween('-30 days', 'now'),
                    'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
                ]);
            }

            // Update wallet balance to match final transaction balance
            $wallet->update(['balance' => max(0, $runningBalance)]);
        }
    }

    private function getTransactionDescription(string $type): string
    {
        return match($type) {
            'deposit' => 'Account deposit via ' . fake()->randomElement(['M-Pesa', 'PayPal', 'Bank Transfer']),
            'withdrawal' => 'Withdrawal to ' . fake()->randomElement(['M-Pesa', 'Bank Account', 'PayPal']),
            'payment' => 'Payment for ' . fake()->randomElement(['project', 'tutoring session', 'course enrollment']),
            'refund' => 'Refund for ' . fake()->randomElement(['cancelled project', 'course refund', 'session cancellation']),
            'commission' => 'Platform commission from ' . fake()->randomElement(['project completion', 'course sale', 'tutoring session']),
            'payout' => 'Payout to ' . fake()->randomElement(['expert', 'tutor', 'content creator']),
            default => 'Transaction processed',
        };
    }
}
