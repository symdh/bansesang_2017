<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Profiler Sections
| -------------------------------------------------------------------------
| This file lets you determine whether or not various sections of Profiler
| data are displayed when the Profiler is enabled.
| Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/profiling.html
|
*/

//view 의 어떤 파일인지를 설정하는 것
$route['default_controller'] = 'main';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
