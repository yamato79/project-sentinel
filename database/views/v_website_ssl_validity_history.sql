CREATE OR REPLACE VIEW v_website_ssl_validity_history AS
SELECT
	website_id,
	raw_data->>'app_location' as app_location,
	(raw_data->'data'->>'is_valid')::boolean as is_valid,
	date_trunc('hour', created_at) as hour
FROM
	monitor_queue
WHERE
	-- SSL Validity Monitor (App\Models\MonitorType::SSL_VALIDITY)
	monitor_type_id = 3
	AND raw_data->>'status' = 'success'
GROUP BY
	hour,
	website_id,
	app_location,
	is_valid
;