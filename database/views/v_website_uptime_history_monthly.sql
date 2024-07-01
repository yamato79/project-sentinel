CREATE OR REPLACE VIEW v_website_uptime_history_monthly AS
SELECT
    websites.website_id,
    websites.name AS website_name,
    websites.address AS website_address,
    subquery.month_start,
    CASE
        WHEN subquery.total_responses > 0 THEN
            (subquery.successful_responses * 100 / subquery.total_responses)::integer
        ELSE
            0
    END AS uptime_percentage
FROM
    (
        SELECT
            monitor_queue.website_id,
            date_trunc('month', monitor_queue.created_at) AS month_start,
            COUNT(*) FILTER (WHERE (monitor_queue.raw_data -> 'data' ->> 'response_code') IS NOT NULL) AS total_responses,
            COUNT(*) FILTER (
                WHERE
                    (monitor_queue.raw_data -> 'data' ->> 'response_code')::integer >= 200 AND
                    (monitor_queue.raw_data -> 'data' ->> 'response_code')::integer <= 299
            ) AS successful_responses
        FROM
            monitor_queue
        WHERE
            (monitor_queue.raw_data -> 'data' ->> 'response_code') IS NOT NULL
        GROUP BY
            monitor_queue.website_id,
            date_trunc('month', monitor_queue.created_at)
    ) subquery
JOIN websites ON subquery.website_id = websites.website_id
ORDER BY
    subquery.month_start;
