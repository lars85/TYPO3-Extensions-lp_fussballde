.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _typoscript:

=========================
TypoScript Reference
=========================

Einstellungen im Plugin (FlexForm) werden bevorzugt. Leere Einstellungen werden mit der TypoScript Konfiguration ersetzt.

Per TypoScript sind die folgenden Einstellungen innerhalb von "plugin.tx\_larspfussballdejs\_pi1" moeglich:


.. ### BEGIN~OF~TABLE ###

.. container:: table-row

    Property
        properties.key

    Data type
        value / stdWrap

    Description
        Muss über Fussball.de für jede Domain erworben werden. Beispiel: "0SSD980SD80SADV98980XC6575XSA568565SASQQ6"

    Default


.. container:: table-row

    Property
        properties.competitionId

    Data type
        value / stdWrap

    Description
        WettberbID. Steht unter jedem Spielplan auf fussball.de. Beispiel: "350735"

    Default


.. container:: table-row

    Property
        properties.season

    Data type
        value / stdWrap

    Description
        Saison. Beispiel: 1314 fuer 2013/14, oder "current" fuer die aktuele Saison

    Default
        current


.. container:: table-row

    Property
        properties.season.swapDay

    Data type
        value / stdWrap

    Description
        Wenn "season" dem Wert "current" entspricht, wird die Saison am Tag "season.swapDay" und dem Monat "season.swapMonth" in jedem Jahr automatisch aktualisiert.

    Default
        1


.. container:: table-row

    Property
        properties.season.swapMonth

    Data type
        value / stdWrap

    Description
        Wenn "season" dem Wert "current" entspricht, wird die Saison am Tag "season.swapDay" und dem Monat "season.swapMonth" in jedem Jahr automatisch aktualisiert.

    Default
        8


.. container:: table-row

    Property
        properties.display

    Data type
        value / stdWrap

    Description
        Typ, der anzeigt werden soll: "games" fuer den Spieltag, oder "table" fuer die Tabelle

    Default
        games


.. ###### END~OF~TABLE ######


Beispiel-Konfiguration
====

::

    plugin.tx\_larspfussballdejs\_pi1 {
        properties {
            key = 0SSD980SD80SADV98980XC6575XSA568565SASQQ6
            competitionId = 350735
            season = current
            season.swapDay =  1
            season.swapMonth = 8
            display = games
        }
    }

    # Je Domain muss bei Fussball.de eine Domain registriert werden.
    # Falls die Seite ueber verschieden Domains erreichbar ist, kann der
    # key ueber die folgende Einstellung bei einer *.eu Domain ausgetauscht werden
    [globalString = ENV:HTTP\_HOST=*.eu]
    plugin.tx\_larspfussballdejs\_pi1.properties.key = 0987654321ASDFGHJKLERQTYUIOCBN56789GHSAJK
    [global]

    # Standard-CSS loeschen
    plugin.tx\_larspfussballdejs\_pi1.\_CSS\_DEFAULT\_STYLE >

..