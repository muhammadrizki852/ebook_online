<?php

namespace Database\Seeders;

use App\Models\Purchase;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        $purchases = [
            [
                'id' => 1, 'user_id' => 1, 'ebook_id' => 2, 'payment_status' => 'approved', 'amount' => 24.99, 'notes' => 'Submitted from app payment page via QRIS GoPay.', 'created_at' => '2026-05-16 14:01:18', 'updated_at' => '2026-05-16 14:01:18'
            ],
            [
                'id' => 2, 'user_id' => 1, 'ebook_id' => 1, 'payment_status' => 'approved', 'amount' => 29.99, 'notes' => 'Submitted from app payment page via QRIS GoPay.', 'created_at' => '2026-05-16 14:42:40', 'updated_at' => '2026-05-16 14:42:40'
            ],
            [
                'id' => 5, 'user_id' => 1, 'ebook_id' => 11, 'payment_status' => 'approved', 'amount' => 135000.00, 'notes' => 'Submitted from app payment page via QRIS GoPay.', 'created_at' => '2026-05-16 15:55:05', 'updated_at' => '2026-05-16 15:55:05'
            ],
            [
                'id' => 7, 'user_id' => 1, 'ebook_id' => 6, 'payment_status' => 'approved', 'amount' => 135000.00, 'notes' => 'Submitted from app payment page via Pembayaran Otomatis.', 'created_at' => '2026-05-19 14:59:12', 'updated_at' => '2026-05-19 14:59:12'
            ],
            [
                'id' => 17, 'user_id' => 1, 'ebook_id' => 14, 'payment_status' => 'approved', 'amount' => 119000.00, 'midtrans_order_id' => 'EBOOK-20260519230455-1-5TMCUS', 'midtrans_transaction_id' => '5daa7b67-dc8b-4d61-86e3-14dbf9047569', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-19 16:06:03', 'created_at' => '2026-05-19 16:04:55', 'updated_at' => '2026-05-19 16:06:03'
            ],
            [
                'id' => 18, 'user_id' => 1, 'ebook_id' => 35, 'payment_status' => 'approved', 'amount' => 106000.00, 'midtrans_order_id' => 'EBOOK-20260519230915-1-HFFYZ0', 'midtrans_transaction_id' => '30717711-1ccf-44df-962c-d7ff62da4857', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-19 16:10:03', 'created_at' => '2026-05-19 16:09:15', 'updated_at' => '2026-05-19 16:10:03'
            ],
            [
                'id' => 19, 'user_id' => 1, 'ebook_id' => 15, 'payment_status' => 'approved', 'amount' => 85000.00, 'midtrans_order_id' => 'EBOOK-20260519231111-1-1SPDIJ', 'midtrans_transaction_id' => 'ea46f1f4-15b3-4514-9350-1a815a139aef', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-19 16:11:56', 'created_at' => '2026-05-19 16:11:11', 'updated_at' => '2026-05-19 16:11:56'
            ],
            [
                'id' => 20, 'user_id' => 4, 'ebook_id' => 6, 'payment_status' => 'approved', 'amount' => 136000.00, 'midtrans_order_id' => 'EBOOK-20260519231454-4-SGFGT4', 'midtrans_transaction_id' => 'e346e1a0-9efe-44a4-a4a8-95b78701d7ea', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-19 16:15:37', 'created_at' => '2026-05-19 16:14:54', 'updated_at' => '2026-05-19 16:15:37'
            ],
            [
                'id' => 21, 'user_id' => 4, 'ebook_id' => 24, 'payment_status' => 'approved', 'amount' => 89000.00, 'midtrans_order_id' => 'EBOOK-20260519231601-4-XCBQLN', 'midtrans_transaction_id' => '425b91ce-3481-44da-be27-8dcc9d4c1c86', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-19 16:16:40', 'created_at' => '2026-05-19 16:16:01', 'updated_at' => '2026-05-19 16:16:40'
            ],
            [
                'id' => 22, 'user_id' => 4, 'ebook_id' => 1, 'payment_status' => 'approved', 'amount' => 81000.00, 'midtrans_order_id' => 'EBOOK-20260519232338-4-JIIQ3W', 'midtrans_transaction_id' => '66c99c7e-98f8-4bd0-8908-74b190e9b4a2', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-19 16:25:16', 'created_at' => '2026-05-19 16:23:38', 'updated_at' => '2026-05-19 16:25:16'
            ],
            [
                'id' => 24, 'user_id' => 4, 'ebook_id' => 45, 'payment_status' => 'approved', 'amount' => 151000.00, 'midtrans_order_id' => 'EBOOK-20260519233430-4-PGRCIL', 'midtrans_transaction_id' => 'c686b419-ba20-4f6e-a819-e5454c0c1bb7', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-19 16:35:09', 'created_at' => '2026-05-19 16:34:30', 'updated_at' => '2026-05-19 16:35:09'
            ],
            [
                'id' => 26, 'user_id' => 4, 'ebook_id' => 21, 'payment_status' => 'approved', 'amount' => 80000.00, 'midtrans_order_id' => 'EBOOK-20260519234340-4-XLF8UQ', 'midtrans_transaction_id' => 'fd1e93b0-c1a5-4ba2-81a2-c4a0c8dbbd94', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-19 16:46:22', 'created_at' => '2026-05-19 16:43:40', 'updated_at' => '2026-05-19 16:46:22'
            ],
            [
                'id' => 27, 'user_id' => 1, 'ebook_id' => 27, 'payment_status' => 'approved', 'amount' => 93000.00, 'midtrans_order_id' => 'EBOOK-20260520200748-1-VRFYLU', 'midtrans_transaction_id' => '6915caae-cde7-4582-bc04-158b74684781', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 13:08:28', 'created_at' => '2026-05-20 13:07:48', 'updated_at' => '2026-05-20 13:08:28'
            ],
            [
                'id' => 28, 'user_id' => 4, 'ebook_id' => 4, 'payment_status' => 'approved', 'amount' => 100000.00, 'midtrans_order_id' => 'EBOOK-20260520201817-4-TL94AJ', 'midtrans_transaction_id' => 'd7b4b231-f79f-46e5-ad5d-a3ea4ec69aee', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 13:18:58', 'created_at' => '2026-05-20 13:18:17', 'updated_at' => '2026-05-20 13:18:58'
            ],
            [
                'id' => 29, 'user_id' => 4, 'ebook_id' => 27, 'payment_status' => 'approved', 'amount' => 93000.00, 'midtrans_order_id' => 'EBOOK-20260520202024-4-TLEGIB', 'midtrans_transaction_id' => '940254ba-6117-4c9e-a954-baaf0da89f28', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 13:21:31', 'created_at' => '2026-05-20 13:20:24', 'updated_at' => '2026-05-20 13:21:31'
            ],
            [
                'id' => 30, 'user_id' => 4, 'ebook_id' => 30, 'payment_status' => 'approved', 'amount' => 146000.00, 'midtrans_order_id' => 'EBOOK-20260520204110-4-KK3RJ1', 'midtrans_transaction_id' => 'b2d91375-0d02-42ee-88de-b4a004602136', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 13:41:53', 'created_at' => '2026-05-20 13:41:10', 'updated_at' => '2026-05-20 13:41:53'
            ],
            [
                'id' => 31, 'user_id' => 4, 'ebook_id' => 3, 'payment_status' => 'approved', 'amount' => 111000.00, 'midtrans_order_id' => 'EBOOK-20260520204731-4-XMXBDN', 'midtrans_transaction_id' => 'fbf29fda-5b05-4586-b734-0dff6840acd5', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 13:48:14', 'created_at' => '2026-05-20 13:47:31', 'updated_at' => '2026-05-20 13:48:14'
            ],
            [
                'id' => 32, 'user_id' => 4, 'ebook_id' => 36, 'payment_status' => 'approved', 'amount' => 133000.00, 'midtrans_order_id' => 'EBOOK-20260520205922-4-6WZWLZ', 'midtrans_transaction_id' => 'fd76406a-f8b2-4ee5-9672-68c53f22e820', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 14:00:02', 'created_at' => '2026-05-20 13:59:22', 'updated_at' => '2026-05-20 14:00:02'
            ],
            [
                'id' => 33, 'user_id' => 4, 'ebook_id' => 9, 'payment_status' => 'approved', 'amount' => 80000.00, 'midtrans_order_id' => 'EBOOK-20260520211129-4-FKSPWA', 'midtrans_transaction_id' => '59648a5f-86af-4a81-8eb2-d7491b5427f4', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 14:12:07', 'created_at' => '2026-05-20 14:11:29', 'updated_at' => '2026-05-20 14:12:07'
            ],
            [
                'id' => 34, 'user_id' => 4, 'ebook_id' => 34, 'payment_status' => 'approved', 'amount' => 97000.00, 'midtrans_order_id' => 'EBOOK-20260520211342-4-XO61GM', 'midtrans_transaction_id' => '35c0d38e-1edb-4d2b-a1b6-e73630366bf1', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 14:14:17', 'created_at' => '2026-05-20 14:13:42', 'updated_at' => '2026-05-20 14:14:17'
            ],
            [
                'id' => 35, 'user_id' => 4, 'ebook_id' => 15, 'payment_status' => 'approved', 'amount' => 85000.00, 'midtrans_order_id' => 'EBOOK-20260520212022-4-JV3COE', 'midtrans_transaction_id' => '569fdfae-317d-4127-abe4-07d7ca4188e4', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 14:20:28', 'created_at' => '2026-05-20 14:20:22', 'updated_at' => '2026-05-20 14:21:00'
            ],
            [
                'id' => 36, 'user_id' => 4, 'ebook_id' => 16, 'payment_status' => 'approved', 'amount' => 93000.00, 'midtrans_order_id' => 'EBOOK-20260520221034-4-FYJT3F', 'midtrans_transaction_id' => '46e405fc-f828-44c0-94be-cb80222126bc', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 15:10:39', 'created_at' => '2026-05-20 15:10:34', 'updated_at' => '2026-05-20 15:11:12'
            ],
            [
                'id' => 37, 'user_id' => 4, 'ebook_id' => 5, 'payment_status' => 'approved', 'amount' => 116000.00, 'midtrans_order_id' => 'EBOOK-20260520224814-4-JZFPDH', 'midtrans_transaction_id' => '04040431-d256-4073-b299-5a3a33691e8b', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 15:48:19', 'created_at' => '2026-05-20 15:48:14', 'updated_at' => '2026-05-20 15:48:52'
            ],
            [
                'id' => 38, 'user_id' => 4, 'ebook_id' => 2, 'payment_status' => 'approved', 'amount' => 121000.00, 'midtrans_order_id' => 'EBOOK-20260520225612-4-UBKUKR', 'midtrans_transaction_id' => 'e062848a-b01f-4f84-b342-21228d75770f', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 15:56:18', 'created_at' => '2026-05-20 15:56:12', 'updated_at' => '2026-05-20 15:56:51'
            ],
            [
                'id' => 39, 'user_id' => 4, 'ebook_id' => 7, 'payment_status' => 'approved', 'amount' => 91000.00, 'midtrans_order_id' => 'EBOOK-20260520230042-4-JMYD37', 'midtrans_transaction_id' => '6784c59e-b820-4f52-ba49-0d23db1546f0', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 16:00:51', 'created_at' => '2026-05-20 16:00:42', 'updated_at' => '2026-05-20 16:01:24'
            ],
            [
                'id' => 40, 'user_id' => 4, 'ebook_id' => 28, 'payment_status' => 'approved', 'amount' => 76000.00, 'midtrans_order_id' => 'EBOOK-20260520230231-4-GSAV1Z', 'midtrans_transaction_id' => '0285885d-3bfc-4954-96ff-6b4ac9d09ffd', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 16:02:37', 'created_at' => '2026-05-20 16:02:31', 'updated_at' => '2026-05-20 16:03:10'
            ],
            [
                'id' => 41, 'user_id' => 4, 'ebook_id' => 14, 'payment_status' => 'approved', 'amount' => 119000.00, 'midtrans_order_id' => 'EBOOK-20260520231401-4-3VJ0JN', 'midtrans_transaction_id' => '7f831ad1-6069-4e73-8ffe-00acb83dcdea', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 16:14:06', 'created_at' => '2026-05-20 16:14:01', 'updated_at' => '2026-05-20 16:15:09'
            ],
            [
                'id' => 42, 'user_id' => 4, 'ebook_id' => 22, 'payment_status' => 'approved', 'amount' => 111000.00, 'midtrans_order_id' => 'EBOOK-20260520232718-4-UQSVVU', 'midtrans_transaction_id' => '15ebf355-f455-46e4-92de-87eaaa125b4e', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 16:27:24', 'created_at' => '2026-05-20 16:27:18', 'updated_at' => '2026-05-20 16:27:57'
            ],
            [
                'id' => 43, 'user_id' => 1, 'ebook_id' => 18, 'payment_status' => 'approved', 'amount' => 90000.00, 'midtrans_order_id' => 'EBOOK-20260520233450-1-6VZHKA', 'midtrans_transaction_id' => 'e727c8a0-36c5-4239-a71e-dbda695ca94d', 'midtrans_payment_type' => 'bank_transfer', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 16:35:12', 'created_at' => '2026-05-20 16:34:50', 'updated_at' => '2026-05-20 16:35:45'
            ],
            [
                'id' => 44, 'user_id' => 1, 'ebook_id' => 33, 'payment_status' => 'approved', 'amount' => 89000.00, 'midtrans_order_id' => 'EBOOK-20260520233638-1-WYQWPQ', 'midtrans_transaction_id' => '2ba7a49e-3b36-4dd3-8d31-7bb906d7c381', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 16:36:45', 'created_at' => '2026-05-20 16:36:38', 'updated_at' => '2026-05-20 16:37:14'
            ],
            [
                'id' => 45, 'user_id' => 1, 'ebook_id' => 17, 'payment_status' => 'approved', 'amount' => 89000.00, 'midtrans_order_id' => 'EBOOK-20260520233922-1-OO9LYA', 'midtrans_transaction_id' => 'e2dc6992-3ab0-4906-a818-c39736d28121', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 16:40:00', 'created_at' => '2026-05-20 16:39:22', 'updated_at' => '2026-05-20 16:40:31'
            ],
            [
                'id' => 46, 'user_id' => 1, 'ebook_id' => 34, 'payment_status' => 'approved', 'amount' => 97000.00, 'midtrans_order_id' => 'EBOOK-20260520234042-1-EGLRU6', 'midtrans_transaction_id' => '95e011e7-6be9-42db-b6d6-5270faa81ab2', 'midtrans_payment_type' => 'credit_card', 'midtrans_transaction_status' => 'capture', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 16:44:31', 'created_at' => '2026-05-20 16:40:42', 'updated_at' => '2026-05-20 16:45:04'
            ],
            [
                'id' => 47, 'user_id' => 1, 'ebook_id' => 30, 'payment_status' => 'approved', 'amount' => 146000.00, 'midtrans_order_id' => 'EBOOK-20260520234729-1-BAJYFI', 'midtrans_transaction_id' => 'b2c4f404-8c7f-4914-af84-eed5fcc7b85a', 'midtrans_payment_type' => 'cstore', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 16:48:21', 'created_at' => '2026-05-20 16:47:29', 'updated_at' => '2026-05-20 16:48:52'
            ],
            [
                'id' => 48, 'user_id' => 1, 'ebook_id' => 22, 'payment_status' => 'approved', 'amount' => 111000.00, 'midtrans_order_id' => 'EBOOK-20260520234906-1-CZKTVN', 'midtrans_transaction_id' => '9511c7ae-b0ef-4181-a5a8-121abb8d6f4e', 'midtrans_payment_type' => 'cstore', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 16:49:17', 'created_at' => '2026-05-20 16:49:06', 'updated_at' => '2026-05-20 16:51:49'
            ],
            [
                'id' => 50, 'user_id' => 1, 'ebook_id' => 28, 'payment_status' => 'approved', 'amount' => 76000.00, 'midtrans_order_id' => 'EBOOK-20260520235547-1-BHQCMF', 'midtrans_transaction_id' => 'fe8da138-9d14-4c47-99b4-b6c2c86c2288', 'midtrans_payment_type' => 'akulaku', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 16:55:58', 'created_at' => '2026-05-20 16:55:47', 'updated_at' => '2026-05-20 16:55:59'
            ],
            [
                'id' => 51, 'user_id' => 1, 'ebook_id' => 36, 'payment_status' => 'approved', 'amount' => 133000.00, 'midtrans_order_id' => 'EBOOK-20260520235907-1-PMINQJ', 'midtrans_transaction_id' => 'e973fa84-2fec-41a5-8eb1-beeae8334453', 'midtrans_payment_type' => 'akulaku', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 16:59:16', 'created_at' => '2026-05-20 16:59:07', 'updated_at' => '2026-05-20 16:59:17'
            ],
            [
                'id' => 52, 'user_id' => 1, 'ebook_id' => 3, 'payment_status' => 'approved', 'amount' => 111000.00, 'midtrans_order_id' => 'EBOOK-20260520235951-1-FZQMP5', 'midtrans_transaction_id' => '666dee9a-8c8d-46cd-9eb6-a1df686e0e9e', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 17:00:03', 'created_at' => '2026-05-20 16:59:51', 'updated_at' => '2026-05-20 17:00:36'
            ],
            [
                'id' => 53, 'user_id' => 1, 'ebook_id' => 16, 'payment_status' => 'approved', 'amount' => 93000.00, 'midtrans_order_id' => 'EBOOK-20260521000058-1-WIWPCI', 'midtrans_transaction_id' => '398a29d1-1490-4805-b6a1-e57a02d7ba7d', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 17:01:04', 'created_at' => '2026-05-20 17:00:58', 'updated_at' => '2026-05-20 17:01:38'
            ],
            [
                'id' => 54, 'user_id' => 1, 'ebook_id' => 21, 'payment_status' => 'approved', 'amount' => 80000.00, 'midtrans_order_id' => 'EBOOK-20260521000323-1-O3FAHG', 'midtrans_transaction_id' => '18930af1-775e-40c6-9e8d-e7646fcb6fe3', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 17:03:30', 'created_at' => '2026-05-20 17:03:23', 'updated_at' => '2026-05-20 17:04:03'
            ],
            [
                'id' => 55, 'user_id' => 5, 'ebook_id' => 1, 'payment_status' => 'approved', 'amount' => 81000.00, 'midtrans_order_id' => 'EBOOK-20260521000531-5-DYJFOF', 'midtrans_transaction_id' => '865cd93d-210d-461d-b518-1218678b406a', 'midtrans_payment_type' => 'bank_transfer', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 17:05:42', 'created_at' => '2026-05-20 17:05:31', 'updated_at' => '2026-05-20 17:06:16'
            ],
            [
                'id' => 56, 'user_id' => 5, 'ebook_id' => 3, 'payment_status' => 'approved', 'amount' => 111000.00, 'midtrans_order_id' => 'EBOOK-20260521000655-5-UBAINU', 'midtrans_transaction_id' => 'db5b8f67-a09b-4da4-9a34-8905b39572ce', 'midtrans_payment_type' => 'credit_card', 'midtrans_transaction_status' => 'capture', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 17:08:25', 'created_at' => '2026-05-20 17:06:55', 'updated_at' => '2026-05-20 17:08:43'
            ],
            [
                'id' => 57, 'user_id' => 5, 'ebook_id' => 15, 'payment_status' => 'approved', 'amount' => 85000.00, 'midtrans_order_id' => 'EBOOK-20260521000905-5-EQMX7P', 'midtrans_transaction_id' => 'bf69b353-4f35-4623-a102-495ea8bd58a5', 'midtrans_payment_type' => 'akulaku', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 17:09:22', 'created_at' => '2026-05-20 17:09:05', 'updated_at' => '2026-05-20 17:09:24'
            ],
            [
                'id' => 58, 'user_id' => 5, 'ebook_id' => 17, 'payment_status' => 'approved', 'amount' => 89000.00, 'midtrans_order_id' => 'EBOOK-20260521000938-5-7AESBK', 'midtrans_transaction_id' => '8e38ac2f-9ee5-404a-9ebb-97c0c718acf7', 'midtrans_payment_type' => 'cstore', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 17:09:45', 'created_at' => '2026-05-20 17:09:38', 'updated_at' => '2026-05-20 17:10:19'
            ],
            [
                'id' => 59, 'user_id' => 5, 'ebook_id' => 24, 'payment_status' => 'approved', 'amount' => 89000.00, 'midtrans_order_id' => 'EBOOK-20260521001436-5-GDTWOV', 'midtrans_transaction_id' => '02dd1f0f-2ba7-4818-81ab-b72240e86b5e', 'midtrans_payment_type' => 'bank_transfer', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 17:15:15', 'created_at' => '2026-05-20 17:14:36', 'updated_at' => '2026-05-20 17:15:48'
            ],
            [
                'id' => 60, 'user_id' => 5, 'ebook_id' => 16, 'payment_status' => 'approved', 'amount' => 93000.00, 'midtrans_order_id' => 'EBOOK-20260521003751-5-XIEVAF', 'midtrans_transaction_id' => '4d78dc60-f02c-4cf0-a924-8b8ba7c53b86', 'midtrans_payment_type' => 'akulaku', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 17:38:29', 'created_at' => '2026-05-20 17:37:51', 'updated_at' => '2026-05-20 17:38:32'
            ],
            [
                'id' => 61, 'user_id' => 5, 'ebook_id' => 30, 'payment_status' => 'approved', 'amount' => 146000.00, 'midtrans_order_id' => 'EBOOK-20260521004231-5-TXK9EW', 'midtrans_transaction_id' => 'c4e82949-bffc-4390-a121-256f7f3bf36a', 'midtrans_payment_type' => 'cstore', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 17:42:38', 'created_at' => '2026-05-20 17:42:31', 'updated_at' => '2026-05-20 17:43:12'
            ],
            [
                'id' => 62, 'user_id' => 5, 'ebook_id' => 27, 'payment_status' => 'approved', 'amount' => 93000.00, 'midtrans_order_id' => 'EBOOK-20260521004413-5-NNKLMF', 'midtrans_transaction_id' => '2d1883a5-6188-4f18-ae8a-b4916c5c6f25', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-20 17:44:20', 'created_at' => '2026-05-20 17:44:13', 'updated_at' => '2026-05-20 17:44:56'
            ],
            [
                'id' => 63, 'user_id' => 5, 'ebook_id' => 22, 'payment_status' => 'approved', 'amount' => 111000.00, 'midtrans_order_id' => 'EBOOK-20260521095626-5-THDCXU', 'midtrans_transaction_id' => '76cb7f9f-e8d4-417d-94e0-398e180a6053', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-21 02:56:29', 'created_at' => '2026-05-21 02:56:26', 'updated_at' => '2026-05-21 02:57:05'
            ],
            [
                'id' => 64, 'user_id' => 5, 'ebook_id' => 35, 'payment_status' => 'approved', 'amount' => 106000.00, 'midtrans_order_id' => 'EBOOK-20260521095723-5-DWFILE', 'midtrans_transaction_id' => 'b33b4811-25fd-496d-a0fb-e00178823913', 'midtrans_payment_type' => 'cstore', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-21 02:57:27', 'created_at' => '2026-05-21 02:57:23', 'updated_at' => '2026-05-21 02:58:04'
            ],
            [
                'id' => 65, 'user_id' => 5, 'ebook_id' => 31, 'payment_status' => 'approved', 'amount' => 126000.00, 'midtrans_order_id' => 'EBOOK-20260521095823-5-F6FZ5S', 'midtrans_transaction_id' => '7c836469-be53-4699-b5a0-c19ef3985c14', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-21 02:58:28', 'created_at' => '2026-05-21 02:58:23', 'updated_at' => '2026-05-21 02:59:05'
            ],
            [
                'id' => 66, 'user_id' => 5, 'ebook_id' => 19, 'payment_status' => 'approved', 'amount' => 50000.00, 'midtrans_order_id' => 'EBOOK-20260521095920-5-6GVB1U', 'midtrans_transaction_id' => '50ce997a-e573-4ef6-8a24-0fbd53398171', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-21 02:59:24', 'created_at' => '2026-05-21 02:59:20', 'updated_at' => '2026-05-21 03:00:00'
            ],
            [
                'id' => 70, 'user_id' => 1, 'ebook_id' => 7, 'payment_status' => 'approved', 'amount' => 91000.00, 'midtrans_order_id' => 'EBOOK-20260521100429-1-DV6OQS', 'midtrans_transaction_id' => '65e75d8b-f74f-4d6a-84f6-d28dd302a53e', 'midtrans_payment_type' => 'bank_transfer', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-21 03:04:36', 'created_at' => '2026-05-21 03:04:29', 'updated_at' => '2026-05-21 03:05:13'
            ],
            [
                'id' => 71, 'user_id' => 1, 'ebook_id' => 5, 'payment_status' => 'approved', 'amount' => 116000.00, 'midtrans_order_id' => 'EBOOK-20260521100617-1-G4TAZT', 'midtrans_transaction_id' => '7e691e18-6c23-47a7-9179-715e8379ad32', 'midtrans_payment_type' => 'akulaku', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-21 03:06:32', 'created_at' => '2026-05-21 03:06:17', 'updated_at' => '2026-05-21 03:06:43'
            ],
            [
                'id' => 72, 'user_id' => 1, 'ebook_id' => 4, 'payment_status' => 'approved', 'amount' => 100000.00, 'midtrans_order_id' => 'EBOOK-20260521100719-1-2OOGJW', 'midtrans_transaction_id' => '1c68f37e-1c95-446e-80b2-1e6d762f665a', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-21 03:07:24', 'created_at' => '2026-05-21 03:07:19', 'updated_at' => '2026-05-21 03:08:00'
            ],
            [
                'id' => 73, 'user_id' => 1, 'ebook_id' => 26, 'payment_status' => 'approved', 'amount' => 99000.00, 'midtrans_order_id' => 'EBOOK-20260521100908-1-YMVWQW', 'midtrans_transaction_id' => '6f667e78-ad74-47ca-9490-04b352752494', 'midtrans_payment_type' => 'cstore', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-21 03:09:22', 'created_at' => '2026-05-21 03:09:08', 'updated_at' => '2026-05-21 03:09:58'
            ],
            [
                'id' => 74, 'user_id' => 1, 'ebook_id' => 8, 'payment_status' => 'approved', 'amount' => 146000.00, 'midtrans_order_id' => 'EBOOK-20260521102426-1-ADTHQE', 'midtrans_transaction_id' => 'd123f7d4-538a-4cc2-b700-cb57a0f0a4e0', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-21 03:24:29', 'created_at' => '2026-05-21 03:24:26', 'updated_at' => '2026-05-21 03:25:05'
            ],
            [
                'id' => 75, 'user_id' => 1, 'ebook_id' => 31, 'payment_status' => 'approved', 'amount' => 126000.00, 'midtrans_order_id' => 'EBOOK-20260521102924-1-WWLDKL', 'midtrans_transaction_id' => '7e0e2845-7f37-4f2e-a6e2-6c17aed5e2ed', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-21 03:29:28', 'created_at' => '2026-05-21 03:29:24', 'updated_at' => '2026-05-21 03:30:02'
            ],
            [
                'id' => 76, 'user_id' => 4, 'ebook_id' => 8, 'payment_status' => 'approved', 'amount' => 146000.00, 'midtrans_order_id' => 'EBOOK-20260522092721-4-9JXTNI', 'midtrans_transaction_id' => 'ff03c6c8-12d7-405d-a311-561905554b6b', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-22 02:27:29', 'created_at' => '2026-05-22 02:27:21', 'updated_at' => '2026-05-22 02:28:03'
            ],
            [
                'id' => 77, 'user_id' => 4, 'ebook_id' => 35, 'payment_status' => 'approved', 'amount' => 106000.00, 'midtrans_order_id' => 'EBOOK-20260522092826-4-NISZVL', 'midtrans_transaction_id' => '68bcf25c-cb2e-4bfb-b88b-dbe88d1a4031', 'midtrans_payment_type' => 'bank_transfer', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-22 02:28:36', 'created_at' => '2026-05-22 02:28:26', 'updated_at' => '2026-05-22 02:29:06'
            ],
            [
                'id' => 78, 'user_id' => 4, 'ebook_id' => 23, 'payment_status' => 'approved', 'amount' => 86000.00, 'midtrans_order_id' => 'EBOOK-20260522092922-4-WCD9HV', 'midtrans_transaction_id' => '88a2e6aa-040b-4756-858e-e4afcaab9750', 'midtrans_payment_type' => 'cstore', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-22 02:29:34', 'created_at' => '2026-05-22 02:29:22', 'updated_at' => '2026-05-22 02:30:09'
            ],
            [
                'id' => 80, 'user_id' => 4, 'ebook_id' => 26, 'payment_status' => 'approved', 'amount' => 99000.00, 'midtrans_order_id' => 'EBOOK-20260522093731-4-CT25KR', 'midtrans_transaction_id' => 'c8292a8e-8b02-4f16-8f49-0993a4d8b217', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-22 02:37:37', 'created_at' => '2026-05-22 02:37:31', 'updated_at' => '2026-05-22 02:38:11'
            ],
            [
                'id' => 81, 'user_id' => 4, 'ebook_id' => 33, 'payment_status' => 'approved', 'amount' => 89000.00, 'midtrans_order_id' => 'EBOOK-20260522093905-4-UZBYXH', 'midtrans_transaction_id' => '59149bfa-5863-429a-8c8f-c2875b6a539e', 'midtrans_payment_type' => 'cstore', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-22 02:39:16', 'created_at' => '2026-05-22 02:39:05', 'updated_at' => '2026-05-22 02:39:51'
            ],
            [
                'id' => 82, 'user_id' => 4, 'ebook_id' => 18, 'payment_status' => 'approved', 'amount' => 90000.00, 'midtrans_order_id' => 'EBOOK-20260522094407-4-D2I5YX', 'midtrans_transaction_id' => 'b592de70-369f-4318-a495-87e8d412520c', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-22 02:44:16', 'created_at' => '2026-05-22 02:44:07', 'updated_at' => '2026-05-22 02:44:49'
            ],
            [
                'id' => 83, 'user_id' => 4, 'ebook_id' => 32, 'payment_status' => 'approved', 'amount' => 100000.00, 'midtrans_order_id' => 'EBOOK-20260522094713-4-ENQ9UT', 'midtrans_transaction_id' => '57cfa091-06df-46dd-9e0a-1fb21e1d5308', 'midtrans_payment_type' => 'qris', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-22 02:47:34', 'created_at' => '2026-05-22 02:47:13', 'updated_at' => '2026-05-22 02:48:07'
            ],
            [
                'id' => 84, 'user_id' => 4, 'ebook_id' => 31, 'payment_status' => 'approved', 'amount' => 126000.00, 'midtrans_order_id' => 'EBOOK-20260522095710-4-DBRCJB', 'midtrans_transaction_id' => '5fd849f4-9246-4498-8d22-9c9a6f00a7ba', 'midtrans_payment_type' => 'akulaku', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-22 02:57:24', 'created_at' => '2026-05-22 02:57:10', 'updated_at' => '2026-05-22 02:57:27'
            ],
            [
                'id' => 85, 'user_id' => 6, 'ebook_id' => 3, 'payment_status' => 'approved', 'amount' => 111000.00, 'midtrans_order_id' => 'EBOOK-20260522110808-6-TGOZCE', 'midtrans_transaction_id' => 'c3c3bfd1-488d-4d76-b118-971ce9c95de8', 'midtrans_payment_type' => 'bank_transfer', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-22 04:08:20', 'created_at' => '2026-05-22 04:08:08', 'updated_at' => '2026-05-22 04:08:54'
            ],
            [
                'id' => 86, 'user_id' => 6, 'ebook_id' => 1, 'payment_status' => 'approved', 'amount' => 81000.00, 'midtrans_order_id' => 'EBOOK-20260522112129-6-UVYXRO', 'midtrans_transaction_id' => 'a481b8b7-8ded-4316-9978-dddec3ae606c', 'midtrans_payment_type' => 'cstore', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-22 04:21:37', 'created_at' => '2026-05-22 04:21:29', 'updated_at' => '2026-05-22 04:22:12'
            ],
            [
                'id' => 87, 'user_id' => 6, 'ebook_id' => 45, 'payment_status' => 'approved', 'amount' => 151000.00, 'midtrans_order_id' => 'EBOOK-20260522145128-6-IMQCBQ', 'midtrans_transaction_id' => '49654305-9fb8-4dcf-b71b-780d2c1b061a', 'midtrans_payment_type' => 'bank_transfer', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-22 07:51:43', 'created_at' => '2026-05-22 07:51:28', 'updated_at' => '2026-05-22 07:52:19'
            ],
            [
                'id' => 88, 'user_id' => 6, 'ebook_id' => 8, 'payment_status' => 'approved', 'amount' => 146000.00, 'midtrans_order_id' => 'EBOOK-20260522150319-6-75NL1R', 'midtrans_transaction_id' => 'b446747a-b0db-46fb-a5ad-23449f3b0dc5', 'midtrans_payment_type' => 'cstore', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-22 08:03:29', 'created_at' => '2026-05-22 08:03:19', 'updated_at' => '2026-05-22 08:04:05'
            ],
            [
                'id' => 89, 'user_id' => 6, 'ebook_id' => 14, 'payment_status' => 'approved', 'amount' => 119000.00, 'midtrans_order_id' => 'EBOOK-20260522151304-6-CPUZVQ', 'midtrans_transaction_id' => '7def4408-4ed3-4ac3-a21e-f92c2a663fd5', 'midtrans_payment_type' => 'akulaku', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-22 08:13:19', 'created_at' => '2026-05-22 08:13:04', 'updated_at' => '2026-05-22 08:13:22'
            ],
            [
                'id' => 90, 'user_id' => 6, 'ebook_id' => 5, 'payment_status' => 'approved', 'amount' => 116000.00, 'midtrans_order_id' => 'EBOOK-20260523110137-6-KSBKVI', 'midtrans_transaction_id' => '68702f50-4adc-4db9-b8df-7a38d525e202', 'midtrans_payment_type' => 'cstore', 'midtrans_transaction_status' => 'settlement', 'midtrans_fraud_status' => 'accept', 'paid_at' => '2026-05-23 04:02:01', 'created_at' => '2026-05-23 04:01:37', 'updated_at' => '2026-05-23 04:02:33'
            ],
        ];

        foreach ($purchases as $purchaseData) {
            Purchase::updateOrCreate(['id' => $purchaseData['id']], $purchaseData);
        }
    }
}
