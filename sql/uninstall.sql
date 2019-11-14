TRUNCATE TABLE `mc_mailchimp_list`;
DROP TABLE `mc_mailchimp_list`;
TRUNCATE TABLE `mc_mailchimp`;
DROP TABLE `mc_mailchimp`;

DELETE FROM `mc_admin_access` WHERE `id_module` IN (
    SELECT `id_module` FROM `mc_module` as m WHERE m.name = 'mailchimp'
);