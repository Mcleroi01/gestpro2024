<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
        CREATE OR REPLACE VIEW semestre_report_views AS
        select
    distinct `t1`.`candidat_id` AS `candidat_id`,
    `t1`.`first_name` AS `first_name`,
    `t1`.`last_name` AS `last_name`,
    `t1`.`email` AS `email`,
    `t1`.`gender` AS `gender`,
    `t1`.`title` AS `title`,
    `t1`.`titletype` AS `titletype`,
    `t1`.`typecode` AS `typecode`,
    `t1`.`birth_date` AS `birth_date`,
    `t1`.`linkedin` AS `linkedin`,
    `t1`.`odcuser_id` AS `odcuser_id`,
    `t1`.`activdate_end` AS `activdate_end`,
    `t1`.`number_day` AS `number_day`,
    `t1`.`type_contrat` AS `type_contrat`,
    `t1`.`entreprise` AS `entreprise`,
    `t1`.`poste` AS `poste`,
    `t1`.`startYear` AS `startYear`,
    `t1`.`namecat` AS `namecat`,
    `t1`.`candid` AS `candid`,
    `t1`.`phone_number` AS `phone_number`,
    `t1`.`university` AS `university`,
    `t1`.`speciality` AS `speciality`
from
    (
        select
            distinct max(`pr`.`candidat_id`) AS `candidat_id`,
            `us`.`first_name` AS `first_name`,
            `us`.`last_name` AS `last_name`,
            `us`.`email` AS `email`,
            `us`.`gender` AS `gender`,
            `ac`.`title` AS `title`,
            `typ`.`title` AS `titletype`,
            `typ`.`code` AS `typecode`,
            `us`.`birth_date` AS `birth_date`,
            `us`.`linkedin` AS `linkedin`,
            `ca`.`odcuser_id` AS `odcuser_id`,
            `ac`.`end_date` AS `activdate_end`,
            `ac`.`number_day` AS `number_day`,
            `typecont`.`libelle` AS `type_contrat`,
            `empl`.`nomboite` AS `entreprise`,
            `empl`.`poste` AS `poste`,
            `ac`.`start_date` AS `startYear`,
            `cat`.`name` AS `namecat`,
            `ca`.`id` AS `candid`,
            `attr`.`phone_number` AS `phone_number`,
            `attr`.`university` AS `university`,
            `attr`.`speciality` AS `speciality`
        from
            (
                (
                    (
                        (
                            (
                                (
                                    (
                                        (
                                            (
                                                `promo3_gestion_centre`.`presences` `pr`
                                                left join `promo3_gestion_centre`.`candidats` `ca` on(`pr`.`candidat_id` = `ca`.`id`)
                                            )
                                            left join `promo3_gestion_centre`.`odcusers` `us` on(`ca`.`odcuser_id` = `us`.`id`)
                                        )
                                        left join `promo3_gestion_centre`.`activites` `ac` on(`ca`.`activite_id` = `ac`.`id`)
                                    )
                                    left join `promo3_gestion_centre`.`categories` `cat` on(`ac`.`categorie_id` = `cat`.`id`)
                                )
                                left join `promo3_gestion_centre`.`activite_type_event` `acty` on(`ac`.`id` = `acty`.`activite_id`)
                            )
                            left join `promo3_gestion_centre`.`type_events` `typ` on(`acty`.`type_event_id` = `typ`.`id`)
                        )
                        left join `promo3_gestion_centre`.`employabilites` `empl` on(`us`.`id` = `empl`.`odcuser_id`)
                    )
                    left join `promo3_gestion_centre`.`type_contrats` `typecont` on(`empl`.`type_contrat_id` = `typecont`.`id`)
                )
                left join (
                    select
                        max(
                            case
                                when `cat`.`label` like '%numero%' then cast(right(`cat`.`value`, 9) as signed)
                                when `cat`.`label` like '%phone%' then cast(right(`cat`.`value`, 9) as signed)
                                when `cat`.`label` like '%telephone%' then cast(right(`cat`.`value`, 9) as signed)
                            end
                        ) AS `phone_number`,
                        max(`cat`.`candidat_id`) AS `candidat_id`,
                        max(
                            case
                                when `cat`.`label` like '%Université%' then `cat`.`value`
                                when `cat`.`label` like '%Etablissement%' then `cat`.`value`
                                when `cat`.`label` like '%Structure%' then `cat`.`value`
                                when `cat`.`label` like '%Entreprise%' then `cat`.`value`
                                when `cat`.`label` like '%Si autre université%' then `cat`.`value`
                                else `od`.`detail_profession`
                            end
                        ) AS `university`,
                        max(
                            case
                                when `cat`.`label` like '%Spécialité ou domaine (étude ou profession)%' then `cat`.`value`
                                when `cat`.`label` like '%Spécialité ou domaine%' then `cat`.`value`
                                when `cat`.`label` like '%Spécialité%' then `cat`.`value`
                                when `cat`.`label` like '%domaine%' then `cat`.`value`
                                when `cat`.`label` like '%Specialite%' then `cat`.`value`
                                when `cat`.`label` like '%Profession%' then `cat`.`value`
                                else `od`.`profession`
                            end
                        ) AS `speciality`
                    from
                        (
                            (
                                `promo3_gestion_centre`.`candidat_attributes` `cat`
                                left join `promo3_gestion_centre`.`candidats` `ca` on(`cat`.`candidat_id` = `ca`.`id`)
                            )
                            left join `promo3_gestion_centre`.`odcusers` `od` on(`ca`.`odcuser_id` = `od`.`id`)
                        )
                    group by
                        `cat`.`candidat_id`
                ) `attr` on(`ca`.`id` = `attr`.`candidat_id`)
            )
        where
            `ac`.`title` is not null
        group by
            `pr`.`candidat_id`,
            `us`.`first_name`,
            `us`.`last_name`,
            `us`.`email`,
            `us`.`gender`,
            `us`.`birth_date`,
            `us`.`linkedin`,
            `ca`.`odcuser_id`,
            `ac`.`end_date`,
            `ac`.`title`,
            `ac`.`number_day`,
            `typecont`.`libelle`,
            `empl`.`nomboite`,
            `empl`.`poste`,
            `ac`.`start_date`,
            `cat`.`name`,
            `typ`.`title`,
            `typ`.`code`,
            `ca`.`id`,
            `attr`.`phone_number`,
            `attr`.`university`,
            `attr`.`speciality`
        order by
            `ac`.`start_date`,
            `ac`.`title`
    ) `t1`");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semestre_report_views');
    }
};
