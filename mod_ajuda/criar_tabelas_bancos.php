<?php
set_time_limit(0);
include_once("class/php/class_conect_db_AWS.php");

echo "INÍCIO DO SCRIPT" . "<br>";
for ($v_db_emp = 1; $v_db_emp <= 112; $v_db_emp++) {

    $id_emp = str_pad($v_db_emp, 4, '0', STR_PAD_LEFT);

    $v_sql = "  DELETE FROM db_emp_" . $id_emp . ".t_rh_hist_col_upload
    WHERE id=nextval('db_emp_" . $id_emp . ".t_rh_hist_col_upload_id_seq'::regclass) AND data_hora=CURRENT_TIMESTAMP;";
    pg_query($conn, $v_sql);

    echo "BANCO ATUALIZADO: " . $id_emp . "<br>";
}

echo "FIM DO SCRIPT";
