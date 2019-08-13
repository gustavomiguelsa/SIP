CONTIKI_PROJECT=board1 board2 board3
all: $(CONTIKI_PROJECT) 

CONTIKI = ../../../..

APPS += mqtt

SMALL=1

CFLAGS += -DPROJECT_CONF_H=\"project-conf.h\"
PROJECT_SOURCEFILES += slip-bridge.c dht22.c

CFLAGS += -DUIP_FALLBACK_INTERFACE=rpl_interface

WITH_WEBSERVER=1
CFLAGS += -DUIP_CONF_TCP=1
CFLAGS += -DWEBSERVER=1
PROJECT_SOURCEFILES += httpd-simple.c

ifeq ($(PREFIX),)
 PREFIX1 = fd00::1/64
 PREFIX2 = fd01::1/64
 PREFIX2 = fd02::1/64
endif

CONTIKI_WITH_IPV6 = 1

include $(CONTIKI)/Makefile.include

$(CONTIKI)/tools/tunslip6:	$(CONTIKI)/tools/tunslip6.c
	(cd $(CONTIKI)/tools && $(MAKE) tunslip6)

connect-router1:	$(CONTIKI)/tools/tunslip6
	sudo $(CONTIKI)/tools/tunslip6 -L -v2 -s ttyUSB0 $(PREFIX1)

connect-router2:	$(CONTIKI)/tools/tunslip6
	sudo $(CONTIKI)/tools/tunslip6 -L -v2 -s ttyUSB1 $(PREFIX2)

connect-router3:	$(CONTIKI)/tools/tunslip6
	sudo $(CONTIKI)/tools/tunslip6 -L -v2 -s ttyUSB2 $(PREFIX3)

connect-router-cooja:	$(CONTIKI)/tools/tunslip6
	sudo $(CONTIKI)/tools/tunslip6 -a 127.0.0.1 $(PREFIX)


