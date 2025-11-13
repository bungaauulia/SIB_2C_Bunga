-- Database: company_bunga

-- DROP DATABASE IF EXISTS company_bunga;

CREATE DATABASE company_bunga
    WITH
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'English_Indonesia.1252'
    LC_CTYPE = 'English_Indonesia.1252'
    LOCALE_PROVIDER = 'libc'
    TABLESPACE = pg_default
    CONNECTION LIMIT = -1
    IS_TEMPLATE = False;

	CREATE OR REPLACE VIEW salary_stats_view AS
SELECT 
    department,
    ROUND(AVG(salary), 2) AS avg_salary,
    MAX(salary) AS max_salary,
    MIN(salary) AS min_salary
FROM employees
GROUP BY department;

CREATE MATERIALIZED VIEW tenure_stats_mv AS
SELECT 
    CASE 
        WHEN AGE(CURRENT_DATE, hire_date) < INTERVAL '1 year' THEN 'Junior'
        WHEN AGE(CURRENT_DATE, hire_date) BETWEEN INTERVAL '1 year' AND INTERVAL '3 years' THEN 'Middle'
        ELSE 'Senior'
    END AS tenure_level,
    COUNT(*) AS total_employees
FROM employees
GROUP BY tenure_level;

CREATE OR REPLACE VIEW employee_overview_view AS
SELECT 
    COUNT(*) AS total_employees,
    SUM(salary) AS total_salary,
    ROUND(AVG(EXTRACT(YEAR FROM AGE(CURRENT_DATE, hire_date)) * 12 +
               EXTRACT(MONTH FROM AGE(CURRENT_DATE, hire_date))), 2) AS avg_tenure_months
FROM employees;

drop materialized view tenure_stats_mv;


CREATE MATERIALIZED VIEW tenure_stats_mv AS
SELECT 
    CASE 
        WHEN AGE(CURRENT_DATE, hire_date) < INTERVAL '1 year' THEN 'Junior'
        WHEN AGE(CURRENT_DATE, hire_date) BETWEEN INTERVAL '1 year' AND INTERVAL '3 years' THEN 'Middle'
        ELSE 'Senior'
    END AS tenure_level,
    COUNT(*) AS total_employees,
    STRING_AGG(first_name || ' ' || last_name, ', ') AS employee_names
FROM employees
GROUP BY tenure_level
ORDER BY total_employees DESC;


CREATE OR REPLACE FUNCTION get_employees_by_salary_range(
    min_salary DECIMAL,
    max_salary DECIMAL
)
RETURNS TABLE (
    id INT,
    full_name text,
    department text,
    "position" varchar,
    salary DECIMAL
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT 
        e.id,
        e.first_name || ' ' || e.last_name AS full_name,
        e.department,
        e.position,
        e.salary
    FROM employees e
    WHERE e.salary BETWEEN min_salary AND max_salary
    ORDER BY e.salary DESC;
END;
$$;

SELECT * FROM get_employees_by_salary_range(3000000, 7000000);

DROP FUNCTION IF EXISTS get_employees_by_salary_range(DECIMAL, DECIMAL);

CREATE OR REPLACE FUNCTION get_employees_by_salary_range(
    min_salary DECIMAL,
    max_salary DECIMAL
)
RETURNS TABLE (
    id INT,
    full_name TEXT,
    department VARCHAR(50),
    "position" VARCHAR(50),
    salary DECIMAL
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT 
        e.id,
        e.first_name || ' ' || e.last_name AS full_name,
        e.department,
        e.position,
        e.salary
    FROM employees e
    WHERE e.salary BETWEEN min_salary AND max_salary
    ORDER BY e.salary DESC;
END;
$$;

SELECT * FROM get_employees_by_salary_range(3000000, 7000000);

DROP FUNCTION IF EXISTS get_department_summary();
CREATE OR REPLACE FUNCTION get_department_summary()
RETURNS TABLE (
    department VARCHAR(50),
    employee_count INT,
    avg_salary DECIMAL,
    total_budget DECIMAL
)
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT 
        e.department,
        COUNT(*)::INT AS employee_count,
        ROUND(AVG(e.salary), 2) AS avg_salary,
        SUM(e.salary) AS total_budget
    FROM employees e
    GROUP BY e.department
    ORDER BY total_budget DESC;
END;
$$;

SELECT * FROM get_department_summary();
