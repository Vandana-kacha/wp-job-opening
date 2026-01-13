<?php
defined('ABSPATH') || exit;

function wp_job_listing_shortcode($atts) {
    // ensure our script is enqueued on pages where shortcode exists
    wp_enqueue_script('wp-job-ajax');

    ob_start();
    ?>
    <div class="wp-job-searchbar">
        <form id="wp-top-search">
            <input type="text" id="job-keyword" name="keyword" placeholder="Job Title, Specialization">
            <button type="submit" class="search-btn">Search Jobs</button>
        </form>
    </div>

    <div class="wp-job-wrapper">
        <div class="row">
            <div class="col-lg-4">
                <h3>Filters</h3>
                <aside class="job-filters"> 
                    <form id="wp-filter-form" class="wp-filter-form">
                        <div class="filter-option">
                            <label>Industry<br>
                                <select name="industry">
                                    <option value="">All</option>
                                    <?php foreach(getIndustry() as $inval) {
                                    ?>
                                    <option value="<?php echo $inval;?>"><?php echo $inval;?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </label>
                        </div>
                        <div class="filter-option"><label>Location<br><input type="text" name="location" placeholder="City or State"></label></div>
                        <div class="filter-option"><label>Department<br><input type="text" name="zipcode" placeholder="Department"></label></div>
                        <div style="margin-top:23px;">
                            <button class="filter-btn">Refine Results</button>
                            <div class="clear-btn" id="clear-filters">Clear Filters</div>
                        </div>
                    </form>
                </aside>
            </div>
            <div class="col-lg-8">
                <section class="job-results">
                    <div class="job-count"></div>
                    <div class="job-listing" id="wp-job-results" aria-live="polite"></div>
                </section>
            </div>
        </div>
        
    </div>
    
    <?php
    return ob_get_clean();
}
add_shortcode('wp_job_list', 'wp_job_listing_shortcode');
