@echo off
echo #############################
echo ##���ܣ�ִ�б���PHP�ű�BAT ##
echo ##author������             ##
echo #############################
echo.

set cron=E:\workspace_php\MyGame\cron
set php=D:\server\php-5.3.3

:input
set /p file=��ѡ��Ҫִ���ļ���
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
choice /C YN /M �Ƿ����ִ�У�
echo.
if errorlevel 2 goto end
if errorlevel 1 goto input

:help
echo ��ǰPHP��װ·����%php%
echo ��ǰ�ű���ȡ·����%cron%
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