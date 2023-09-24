.DEFAULT_GOAL := open

ACTIVATE = . venv/bin/activate;
ASSETS = $(shell find assets -type f -name '*')
PROJECT_NAME = qbee

COL_WIDTH = 10
FORMAT_YELLOW = 33
FORMAT_BOLD_YELLOW = \e[1;$(FORMAT_YELLOW)m
FORMAT_END = \e[0m
FORMAT_UNDERLINE = \e[4m

exec = @docker exec -it $$(docker ps -asf "name=$(1)" | grep -v CONTAINER | cut -d' ' -f1) bash -c "$(2)"

define usage
	@printf "Usage: make target\n\n"
	@printf "$(FORMAT_UNDERLINE)target$(FORMAT_END):\n"
	@grep -E "^[A-Za-z0-9_ -]*:.*#" $< | while read -r l; do printf "  $(FORMAT_BOLD_YELLOW)%-$(COL_WIDTH)s$(FORMAT_END)$$(echo $$l | cut -f2- -d'#')\n" $$(echo $$l | cut -f1 -d':'); done
endef

.git/hooks/pre-commit:
	$(ACTIVATE) pre-commit install
	@touch $@

venv: venv/.touchfile .git/hooks/pre-commit
venv/.touchfile: requirements.txt
	test -d venv || virtualenv venv
	$(ACTIVATE) pip install -Ur requirements.txt
	@touch $@

.PHONY: help
help: Makefile  # Print this message
	$(call usage)

build: venv venv/.build_touchfile  # Build image
venv/.build_touchfile: Dockerfile $(ASSETS)
	docker build -t $(PROJECT_NAME)-site --target site .
	docker build -t $(PROJECT_NAME)-db --target db .
	@touch $@

.PHONY: start
start: build  # Start service
	@docker compose up --detach
	$(call exec,site,/tmp/wait-for-mysql.sh)

.PHONY: restart
restart: stop start  # Restart service

.PHONY: sh
sh bash:  # Bash into qbee container
	$(call exec,site,bash)

.PHONY: mysql
mysql:  # Bash into mysql container
	$(call exec,mysql,bash)

.PHONY: stop
stop:  # Stop service
	@docker compose down --remove-orphans

.PHONY: open
open: start
	@open http://localhost
