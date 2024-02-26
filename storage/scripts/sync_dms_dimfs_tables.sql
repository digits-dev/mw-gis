INSERT INTO dtc_digits_dms.items (`digits_code`, `upc_code`, `upc_code2`, `upc_code3`, `upc_code4`, `upc_code5`, `item_description`, `brand`, `has_serial`, `store_cost`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) SELECT  
       a.`digits_code`
       ,a.`upc_code`
       ,a.`upc_code2`
       ,a.`upc_code3`
       ,a.`upc_code4`
       ,a.`upc_code5`
       ,a.`item_description`
       ,c.`brand_description`
       ,a.`has_serial`
       ,a.`dtp_rf`
       ,a.`created_by`
       ,a.`updated_by`
       ,a.`created_at`
       ,a.`updated_at`
       ,a.`deleted_at`
FROM dtc_digits_imfs_v3.item_masters a
JOIN dtc_digits_imfs_v3.brands c ON a.brands_id = c.id
WHERE a.digits_code is not null and a.digits_code not in (SELECT b.digits_code FROM dtc_digits_dms.items b);

UPDATE dtc_digits_dms.items m, dtc_digits_imfs_v3.item_masters t 
SET m.store_cost = t.dtp_rf
WHERE t.digits_code = m.digits_code;

UPDATE dtc_digits_dms.items m
SET m.store_cost = '0.00'
WHERE m.store_cost is null;


UPDATE dtc_digits_dms.items m, dtc_digits_imfs_v3.item_masters t 
SET m.has_serial = t.has_serial
WHERE t.digits_code = m.digits_code;

INSERT INTO dtc_digits_dms.items (`digits_code`, `upc_code`, `item_description`, `brand`, `store_cost`) SELECT  
       a.`digits_code`
       ,a.`jan_no`
       ,a.`item_description`
       ,c.`brand_description`
       ,a.`store_cost`
FROM dtc_digits_imfs_v3.gacha_item_masters a
JOIN dtc_digits_imfs_v3.gacha_brands c ON a.gacha_brands_id = c.id
WHERE a.digits_code is not null and a.digits_code not in (SELECT b.digits_code FROM dtc_digits_dms.items b);
