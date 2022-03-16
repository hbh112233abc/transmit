@echo off
set pwd=%~dp0
set thrift="%pwd%thrift"
thrift -gen php:server,nsglobal=bingher\transmit "%pwd%transmit.thrift"
