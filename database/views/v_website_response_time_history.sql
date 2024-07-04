CREATE OR REPLACE VIEW v_website_response_time_history AS
SELECT
	website_id,
	raw_data->>'app_location' as app_location,
	ROUND(AVG((raw_data->'data'->>'response_time')::numeric), 2) as avg_response_time_ms,
	date_trunc('hour', created_at) as hour
FROM
	monitor_queue
WHERE
	-- Response Time Monitor (App\Models\MonitorType::RESPONSE_TIME)
	monitor_type_id = 2
	AND raw_data->>'status' = 'success'
GROUP BY
	hour,
	website_id,
	app_location
ORDER BY
	hour DESC
;
