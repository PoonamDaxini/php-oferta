<!DOCTYPE html>
<html>
    <head>
        <title>Oferta</title>
        <link rel="stylesheet" href="/css/bootstrap.css"/>
        <script src="/js/jquery.js"></script>
        <script src="/js/bootstrap.min.js"></script>


    </head>
    <body>

        <nav class="navbar navbar-default navbar-static-top" role="navigation">            
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/">Auction : Cricket</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">

                </div><!--/.nav-collapse -->
            </div>
        </nav>

        <div class="container">

            <?php foreach ($teams as $cap => $player) {
                ?>
                <div class="col-lg-4">
                    <table class="table table-bordered table-striped"><tr>
                            <th colspan="2"> <?php echo $captains[$cap]; ?>
                            </th>
                        </tr>
                        <?php
                        foreach ($player as $mem) {
                            ?>

                            <tr>
                                <td><?php echo $mem['name'] ?></td>
                                <td>
                                    <?php echo $mem['bid_value'] ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
                <?php
            }
            ?>

        </div>
    </body>
</html>