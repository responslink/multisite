<?php

/**
 * @file
 * Provides views data for the QR generator module.
 */

 function custom_qr_generator_views_data() {
   // This table references the {node} table. The declaration below creates an
   // 'implicit' relationship to the node table, so that when 'node' is the base
   // table, the fields are automatically available.
   $data['custom_qr_generator_stats']['table']['join'] = array(
     // Index this array by the table name to which this table refers.
     // 'left_field' is the primary key in the referenced table.
     // 'field' is the foreign key in this table.
     'node' => array(
       'left_field' => 'nid',
       'field' => 'qrnid',
     ),
   );

   // Next, describe each of the individual fields in this table to Views. This
   // is done by describing $data['example_table']['FIELD_NAME']. This part of
   // the array may then have further entries:
   //   - title: The label for the table field, as presented in Views.
   //   - help: The description text for the table field.
   //   - relation: A description of any relation handler for the table field.
   //   - field: A description of any field handler for the table field.
   //   - sort: A description of any sort handler for the table field.
   //   - filter: A description of any filter handler for the table field.
   //   - argument: A description of any argument handler for the table field.
   //   - area: A description of any handler for adding content to header,
   //     footer or as no result behaviour.
   //
   // The handler descriptions are described with examples below.

   // Node ID table field.
   $data['custom_qr_generator_stats']['qrnid'] = array(
     'title' => t('QR NID'),
     'help' => t('Some example content that references a node.'),
     // The nid is a foreign key to the {node} table. This allows us to (easily)
     // add a relationship handler for this table field, making all the table
     // fields for the related node available.
     'relationship' => array(
       'base' => 'node', // The name of the table to join with
       'field' => 'nid', // The name of the field to join with
       'handler' => 'views_handler_relationship',
       'label' => t('Example node'),
     ),
   );

  return $data;
}
