<?php
	$result = json_decode('{"action":"pay","payment_id":428156602,"status":"sandbox","version":3,"type":"buy","paytype":"card","public_key":"i97603769660","acq_id":414963,"order_id":"3_20170607013247_1","liqpay_order_id":"X6AWR09T1496788388017670","description":"Оплата за курс Летняя Школа","sender_first_name":"Феанор1","sender_last_name":"Нолдо1","sender_card_mask2":"414949*64","sender_card_bank":"pb","sender_card_type":"visa","sender_card_country":804,"ip":"77.122.31.185","amount":12312.0,"currency":"UAH","sender_commission":0.0,"receiver_commission":338.58,"agent_commission":0.0,"amount_debit":12312.0,"amount_credit":12312.0,"commission_debit":0.0,"commission_credit":338.58,"currency_debit":"UAH","currency_credit":"UAH","sender_bonus":0.0,"amount_bonus":0.0,"mpi_eci":"7","is_3ds":false,"create_date":1496788388056,"end_date":1496788388056,"transaction_id":428156602}');
	var_dump($result->amount);
?>