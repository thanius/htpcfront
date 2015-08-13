#!/bin/bash
kill -s TERM $(pidof "chrome -kiosk --window-size=1920,1080 --window-position=0,0") && sleep 2
dbus-send --system --print-reply --dest="org.freedesktop.ConsoleKit" /org/freedesktop/ConsoleKit/Manager org.freedesktop.ConsoleKit.Manager.Stop
