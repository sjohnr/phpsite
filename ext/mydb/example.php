<?php

set_include_path(get_include_path().PATH_SEPARATOR.realpath(__file__));

include_once 'Model.class.php';

$model = new Model('employee');
$x     = $_GET['x'];
switch ($x) {
	case 'create':
		$employeeId = $model->create(array(
			'name' => 'Bob',
			'isActive' => true,
			'departmentId' => 2,
		));
		
		print_r(array('employeeId' => $employeeId));
	break;
	case 'retrieve':
		$employeeId = $_GET['employeeId'];
		$employee   = $model->retrieve($employeeId);
		
		print_r($employee);
	break;
	case 'update':
		$employeeId = $_GET['employeeId'];
		$isActive   = $_GET['isActive'];
		$model->update($employeeId, array('isActive' => $isActive));
	break;
	case 'delete':
		$employeeId = $_GET['employeeId'];
		$model->delete($employeeId);
	break;
	case 'query':
		$employees  = $model->query("SELECT employee.* FROM employee WHERE employee.isActive = :isActive AND employee.departmentId = :departmentId", array(
			'isActive' => true,
			'departmentId' => 2,
		));
		//$employees  = $model->query("SELECT employee.*, department.*, company.* FROM employee, department, company WHERE employee.isActive = true OR employee.isActive = :isActive AND employee.department.level > :level AND employee.department.company.companyId = :companyId", array(
		//	'isActive' => true,
		//	'companyId' => 5,
		//));
		
		print_r($employees);
	break;
}

?>
