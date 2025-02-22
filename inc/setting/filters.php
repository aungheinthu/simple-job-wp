<div class="wrap">
    <form id="sjw_filters" method="post">
        <table class="form-table">
            <tr>
                <th scope="row">Select filters that display on front-end</th>
                <td>
                    
                    <?php
                    $filter_options = [
                        'sjw_enable_category_filter' => 'Enable the Job Category Filter',
                        'sjw_enable_type_filter'     => 'Enable the Job Type Filter',
                        'sjw_enable_location_filter' => 'Enable the Job Location Filter',
                        'sjw_enable_search_bar'      => 'Enable the Search Bar'
                    ];

                    foreach ($filter_options as $option_name => $label) {
                        $is_checked = get_option($option_name, 'no') === 'yes' ? 'checked' : '';
                        echo "<label>
                                    <input type='checkbox' name='" . esc_attr($option_name) . "' id='" . esc_attr($option_name) . "' value='yes' $is_checked />
                                    $label
                            </label><br /><br />";
                    }
                    ?>
    
                </td>
            </tr>
        </table>

        <div class="sjw-submit-button-wrap">
            <button class="button button-primary button-large">Save Changes</button>
            <span class="spinner"></span>
        </div>
    </form>
</div>
