# MODEL

v event manageri bude mozne vytvorit viacero eventov

event:
- slug
- datum zaciatku registracie
- info v SK/EN [v templatoch]
- email template v SK/EN [ v templatoch]
- miesta_konania
- organizer_emails 

miesto_konania:
- datum konca registracie 
- datum konania event
- informacie_o_mieste SK/EN
- kapacita
- kapacita_prekrocena_text SK/EN [template]
- text_notification SK/EN [template]
- date_notification_treshold

registracia:
- meno/priezvisko
- email
- jazyk
- registracne cislo (vygenerovane)
- miesto_konania

mailinglist: [rovno sa pridavat]
- email
- meno/priezvisko
- datum pridania
- jazyk



----------------------------------------

# TEMPLATES

texty uvedene vyssie budu pisane v syntaxy Twig a naplneny vhodnym kontextom.

priklad pre text_notification_en
```
Hi {{registration.first_name}},

you registered to {{registration.miesto_konania.event.name}} which will be on {{registration.miesto_konania.date}}.

The address:
{{registraion.miesto_konania.address}}

You registraion number is {{registration.number}}.
```

-----------------------------------------

# ADMIN

- pridat/upravit event
- pridat/upravit/zmazat miesto konania
- rozoslat testovacie emaily
- vyrenderovat jednotlive texty pre kontrolu


# INE OTAZKY
- kto bude email provider gmail/aws/univerzita?

