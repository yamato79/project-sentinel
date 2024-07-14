CREATE OR REPLACE VIEW v_website_domain_expiration_history AS
SELECT
	website_id,
	raw_data->>'app_location' as app_location,
	ROUND((raw_data->'data'->>'expires_in')::numeric, 2) as expires_in,
	raw_data->'data'->>'expires_in_date' as expires_in_date,
	created_at
FROM
	monitor_queue
WHERE
	-- Domain Expiration Monitor (App\Models\MonitorType::DNS_EXPIRATION)
	monitor_type_id = 5
	AND raw_data->>'status' = 'success'
;