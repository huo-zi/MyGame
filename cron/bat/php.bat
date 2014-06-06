@echo off
echo ┌──────────────┐
echo │Function：执行本地PHP脚本BAT│
echo │Author：火子                │
echo │Date：2014-06-05            │ 
echo └──────────────┘
echo.

set cron=E:\workspace_php\MyGame\cron
set php=D:\server\php-5.3.3

:input
set /p file=请选择要执行文件：
echo.
IF "%file%"=="help" goto help
IF "%file%"=="" goto notInput
IF NOT EXIST %cron%\%file% goto notExist else goto php

:php
echo ---------------------
echo cron:begining
%php%\php %cron%\%file%
echo.
echo cron:end.
echo ---------------------
goto choice

:choice
echo.
choice /C YN /M 是否继续执行：
echo.
if errorlevel 2 goto end
if errorlevel 1 goto input

:help
echo 当前PHP安装路径：%php%
echo 当前脚本读取路径：%cron%
goto choice

:notExist
echo file:%cron%\%file% does not exist.
goto choice

:notInput
echo pleace input fileName
goto choice

:end
echo See You..

pause