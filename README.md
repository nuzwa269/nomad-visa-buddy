Nomad Visa Hub - WordPress Plugin
---------------------------------

This plugin provides a SaaS-like module to manage and display Digital Nomad Visa information for multiple countries.

Folder structure and files are ready to be zipped in a folder named "nomad-visa-hub".

Activation will create custom tables in the WordPress database.

Shortcodes:
[nvb_country_directory detail_page="country-detail"] — ممالک کی فہرست (ڈائریکٹری) دکھاتا ہے۔ `detail_page` میں اُس پیج کا سلگ یا ID دیں جہاں `[nvb_country_detail]` لگا ہے۔
[nvb_country_detail] — کسی منتخب ملک کی تفصیل (Visa programs، اہلیت، دستاویزات وغیرہ) لوڈ کرتا ہے۔
[nvb_application_guide] — ملک کی اپلیکیشن گائیڈ کے مراحل دکھاتا ہے۔
[nvb_document_checklist] — مطلوبہ دستاویزات کی چیک لسٹ رینڈر کرتا ہے۔
[nvb_cost_of_living] — منتخب ملک کے اخراجاتِ  کا بلاک دکھاتا ہے۔

REST endpoints:
- /wp-json/nvb/v1/countries
- /wp-json/nvb/v1/country/{id}
- /wp-json/nvb/v1/visa/{id}
- /wp-json/nvb/v1/checklist/{id}

Security:
- Nonces, capability checks, sanitization, and prepared statements are used.

Include this folder structure in your WordPress plugins directory and activate the plugin.
