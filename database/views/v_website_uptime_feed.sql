CREATE OR REPLACE VIEW v_website_uptime_feed AS
SELECT
	website_id,
	raw_data->>'app_location' as app_location,
	ROUND(COUNT(CASE WHEN (raw_data->'data'->>'response_code')::int BETWEEN 200 AND 299 THEN 1 END) * 100.0 / COUNT(*), 0) as avg_uptime_percent,
	date_trunc('minute', created_at) as minute
FROM
	monitor_queue
WHERE
	-- Response Code Monitor (App\Models\MonitorType::RESPONSE_CODE)
	monitor_type_id = 1
	AND raw_data->>'status' = 'success'
GROUP BY
	minute,
	website_id,
	app_location
ORDER BY
	minute DESC
;