<?php
/**
 * Created by 10bee
 * User: tjiang
 * Date: 7/23/2015
 * Time: 3:31 PM
 */

return [
    'facilityUrl' => "http://dev_pcr1.ami.local:8000/init?wampus=",
    'specAdminUrl' => "/adminHome",
    'techUrl' => "http://dev_pcr2.ami.local:8000/technician/portal/init?wampus=",

    'clientHost' => [
        "dev_pcr2.ami.local",
        "localhost"
    ],
    'adminConn' => "mysql_micor_admin",

    'tb_users' => "micor_authapi.users",
    'tb_roles' => "micor_authapi.roles",
    'tb_users_roles' => "micor_authapi.users_roles",
    'tb_techs' => "micor_technician.technician_profile",
    'tb_techs_roles' => "micor_technician.technician_role_assignment_history",

    'no_role_err_msg' => "No roles assigned to this technician.",
    'username_prefix_for_user' => "user.",
];

