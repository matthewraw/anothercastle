<?php
/*
Filename: pageButtons.php
Author: Matthew Raw
Date Created: 11/6/17
Last Updated: 11/6/17
Description: Includes file for store item printing. Uses bootstrap to print off columns of items. Requires SQL query stored in $resultSet above in pages where used.
*/
//if there are more than 15 items in the total products to be displayed, create page numbers
                        if ($totalCount > 15) {
                                    
                                    echo '<div class="pageButtons">';
                                    //set number of pages required.
                                    $numberOfPages = ceil($totalCount/15); 

                                    //add buttons to form
                                    for ($i=0; $i < $numberOfPages; $i++) {
                                        $button = "<a href='?page=".($i+1)."' class='pageButton btn btn-default"; 

                                        if ($i + 1 == $pageNumber) {
                                            $button = $button." thisPage";
                                        }

                                        $button = $button."'>".($i+1)."</a>";

                                        echo $button;
                                    }

                                    $button = "<a href='?page=viewAll' class='allButton btn btn-default";

                                    if (isset($_GET['page']) && $_GET['page'] == 'viewAll') {
                                        $button = $button." thisPage";
                                    }

                                    $button = $button."'>View All</a>";

                                    echo $button;
                                    echo '</div>';
                        }

?>