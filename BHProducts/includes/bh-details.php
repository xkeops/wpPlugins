<?php


//meta box
function add_custom_meta_box()
{
    add_meta_box("demo-meta-box", "Details", "custom_meta_box_markup", "d3_product", "side", "high", null);
}

add_action("add_meta_boxes", "add_custom_meta_box");

function custom_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <div class="misc-pub-section misc-pub-post-status">
            <label for="meta-box-price">Price</label>
            <input name="meta-box-price" id="meta-box-price" type="text" value="<?php echo get_post_meta($object->ID, "meta-box-price", true); ?>">

            <br>

            <label for="meta-box-dropdown">Route</label>
            <select name="meta-box-dropdown" id="meta-box-dropdown">
                <?php 
                    $option_values = array(1, 2, 3);

                    foreach($option_values as $key => $value) 
                    {
                        if($value == get_post_meta($object->ID, "meta-box-dropdown", true))
                        {
                            ?>
                                <option selected><?php echo $value; ?></option>
                            <?php    
                        }
                        else
                        {
                            ?>
                                <option><?php echo $value; ?></option>
                            <?php
                        }
                    }
                ?>
            </select>

            <br>

            <label for="meta-box-gift">Gift</label>
            <?php
                $gift_value = get_post_meta($object->ID, "meta-box-gift", true);

                if($gift_value == "")
                {
                    ?>
                        <input name="meta-box-gift" id="meta-box-gift" type="checkbox" value="true">
                    <?php
                }
                else if($gift_value == "true")
                {
                    ?>  
                        <input name="meta-box-gift" type="checkbox" value="true" checked>
                    <?php
                }
            ?>
        </div>
    <?php  
}

function save_custom_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_psp_product", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "d3_product";
    if($slug != $post->post_type)
        return $post_id;

    $meta_box_price_value = "";
    $meta_box_dropdown_value = "";
    $meta_box_gift_value = "";

    if(isset($_POST["meta-box-price"]))
    {
        $meta_box_price_value = $_POST["meta-box-price"];
    }   
    update_post_meta($post_id, "meta-box-price", $meta_box_price_value);

    if(isset($_POST["meta-box-dropdown"]))
    {
        $meta_box_dropdown_value = $_POST["meta-box-dropdown"];
    }   
    update_post_meta($post_id, "meta-box-dropdown", $meta_box_dropdown_value);

    if(isset($_POST["meta-box-gift"]))
    {
        $meta_box_gift_value = $_POST["meta-box-gift"];
    }   
    update_post_meta($post_id, "meta-box-gift", $meta_box_gift_value);
}

add_action("save_post", "save_custom_meta_box", 10, 3);


/*
	function remove_custom_field_meta_box()
	{
	    remove_meta_box("meta-box-text", "post", "normal");
	}
	add_action("demo-meta-box", "remove_custom_field_meta_box");
*/

?>