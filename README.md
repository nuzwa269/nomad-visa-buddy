Nomad Visa Hub - WordPress Plugin
---------------------------------

This plugin provides a SaaS-like module to manage and display Digital Nomad Visa information for multiple countries.

Folder structure and files are ready to be zipped in a folder named "nomad-visa-hub".

Activation will create custom tables in the WordPress database.

Shortcodes:
- [nvb_country_directory]
- [nvb_country_detail id="portugal"]
- [nvb_application_guide id="estonia"]
- [nvb_document_checklist id="georgia"]
- [nvb_cost_of_living id="malaysia"]

REST endpoints:
- /wp-json/nvb/v1/countries
- /wp-json/nvb/v1/country/{id}
- /wp-json/nvb/v1/visa/{id}
- /wp-json/nvb/v1/checklist/{id}

Security:
- Nonces, capability checks, sanitization, and prepared statements are used.

Include this folder structure in your WordPress plugins directory and activate the plugin.
