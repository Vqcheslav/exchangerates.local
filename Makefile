arg=$(filter-out $@,$(MAKECMDGOALS))
start:
	docker compose up $(arg)
stop:
	docker compose stop