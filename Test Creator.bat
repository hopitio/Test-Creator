tasklist /FI "IMAGENAME eq USBWebserver.exe" /FO CSV > search.log

FINDSTR USBWebserver.exe search.log > found.log

FOR /F %%A IN (found.log) DO IF %%~zA EQU 0 GOTO end

start bin/USBWebserver.exe

:end

del search.log
del found.log

start "Test Creator" bin/root/startup.html