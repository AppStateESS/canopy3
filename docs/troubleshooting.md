# Troubleshooting

System controller not found but directory exists.
: Dashboards and Plugins have to be registered to the system before autoloading will allow access. Check for the missing system in your `systems/registered.json` file. If the system is missing, you can register is with the `/bin/registerSystem` script.
