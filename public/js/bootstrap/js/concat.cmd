@echo off
for /r %%i in (*.js) DO call :concat %%i
goto :eof

:concat
cat %1
goto :eof