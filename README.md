# Kario Mart

## Project Description

Kario Mart is a web application developed with PHP and Oracle. The goal of the project is to connect a PHP-based website to an Oracle database in order to manage races, vehicles, participants, race times, and award ceremonies.

The application includes a login page where the user enters their Oracle account credentials. After logging in, the user is redirected to a main menu with different options. The project also uses PHP sessions to keep track of selected races and user actions throughout the different forms.

## Technologies Used

PHP, Oracle SQL, HTML, CSS, and sessions with `$_SESSION`.

## A — Register a Vehicle

This option allows the user to add a new vehicle to the database.

The form asks for information such as the vehicle description, color, consumption, purchase date, price, vehicle group, fuel type, and owner. Some fields use dropdown menus to make sure that the selected values already exist in the database.

The vehicle code is generated automatically using the first five characters of the description. If the generated code already exists, three random digits are added to make it unique.

## B — Display Vehicles

This option shows a list of all registered vehicles.

For each vehicle, the application displays information such as its code, description, color, fuel type, consumption, and owner. It also calculates the cost of driving 100 km using the fuel unit price and the vehicle consumption.

## C — Register Participants in a Race

This section allows users to register participants in an open race.

First, the user selects an open race. Then, the real start date and time of the race are entered. After that, the application allows the selection of users with enough balance to pay the registration fee, together with their character and vehicle.

PHP sessions are used to keep the selected race code available across the different files involved in the registration process.

## D — Enter Participants’ Times

This option is used to enter the race times of all participants.

The user selects a closed race and enters each participant’s time in `MM:SS` format. The application converts the times, updates the database, and calculates the best time of the race.

The result is then displayed to the user, indicating the fastest participant and their final time.

## E — View Race Participants

This option allows the user to check the participants of a selected race.

If no times have been entered yet, the application displays a simple list of registered participants. If the race times already exist, it shows the race ranking ordered from fastest to slowest.

Participants without a valid time are displayed as `Abandoned`.

## F — Award Ceremonies

This section manages the award ceremonies that take place after each race.

A new table called `Cerimonies` was created to store the race code, character alias, position, amount of Pinky, and the date and time of the ceremony.

After entering the race times, the application automatically inserts the top three classified participants into the ceremony table. The ceremony date and time are calculated as two hours after the slowest participant finishes the race.

The amount of Pinky assigned to each character depends on their final position and their participation in recent races.

## F1 — Total Amount of Pinky Between Two Dates

This option allows the user to select a start date and an end date.

The application calculates the total amount of Pinky consumed by each character during ceremonies held between those two dates.

If no characters participated in ceremonies during that period, the application displays a message informing the user.

## F2 — Ceremony History of a Character

This option allows the user to select a character and view their ceremony history.

The application displays the races in which the character participated in an award ceremony, including the race name, race date, final position, and amount of Pinky consumed.

If the selected character has not participated in any ceremony, the application shows a message indicating that no records were found.

