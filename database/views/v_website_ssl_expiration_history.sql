CREATE OR REPLACE VIEW v_website_ssl_expiration_history AS
SELECT
	website_id,
	raw_data->>'app_location' as app_location,
	ROUND((raw_data->'data'->>'expires_in')::numeric) as expires_in,
	raw_data->'data'->>'expires_in_date' as expires_in_date,
	date_trunc('hour', created_at) as hour
FROM
	monitor_queue
WHERE
	-- SSL Expiration Monitor (App\Models\MonitorType::SSL_EXPIRATION)
	monitor_type_id = 4
	AND raw_data->>'status' = 'success'
ORDER BY
	hour DESC
;