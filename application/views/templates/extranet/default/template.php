<?php echo doctype('html5'); ?>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="<?php echo $this->config->item('cms_site_desc'); ?>" />
        <meta name="author" content="" />
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/ico/favicon.png" />

        <title><?php echo (isset($_title)) ? $_title . ' | ' : ''; ?><?php echo $this->config->item('cms_site_name'); ?></title>

        <!-- Font awesome -->
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" />

        <!-- Bootstrap core CSS -->
        <?php echo $_css; ?>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div id="spin"></div><!-- end #spin -->
    <?php if ($this->user->is_logged_in()) : ?>
        <header class="navbar navbar-fixed-top inner">
            <!--div class="col-xs-9 "></div-->
            <div class="welcome-user col-xs-12 col-md-3 col-md-offset-9">
                <p>
                    Hola, <span><?php echo $this->user->name; ?></span>
                </p>
                <figure>
                <?php if ($this->user->avatar != '') : ?>
                    <img class="img-responsive" src="<?php echo $this->user->avatar; ?>" alt="Avatar de <?php echo $this->user->name; ?>" />
                <?php else : ?>
                    <i class="fa fa-user fa-lg"></i>
                <?php endif; ?>
                </figure>
                <aside class="info_user">
                    <figure>
                    <?php if ($this->user->avatar != '') : ?>
                        <img class="img-responsive" src="<?php echo $this->user->avatar; ?>" alt="Avatar de <?php echo $this->user->name; ?>" />
                    <?php else : ?>
                        <i class="fa fa-user fa-5x"></i>
                    <?php endif; ?>
                    </figure>
                    <div class="detail_info_user">
                        <h2><?php echo $this->user->name; ?></h2>
                        <p><?php echo $this->user->email; ?></p>
                    </div>
                    <div class="actions_users">
                        <a href="#" class="edit-user pull-left">editar perfil</a>
                        <a href="<?php echo base_url(); ?>extranet/main/logout" class="logout pull-right">[cerrar sesión]</a>
                    </div>
                </aside><!-- end info_user -->
            </div><!-- end welcome-user -->
        </header><!-- end header -->

        <aside class="sidebar-left">
            <h1 class="logo text-center">
                <a href="<?php echo base_url(); ?>extranet" title="<?php echo $this->config->item('cms_site_name') ?>"><img class="img-responsive" src="<?php echo base_url(); ?>assets/images/logo-forget.png" alt="<?php echo $this->config->item('cms_site_name') ?>" /></a>
            </h1><!-- end logo -->
        </aside><!-- end sidebar-left -->

        <div class="main-container">
            <div class="main-content">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <?php foreach($_warning as $_msg): ?>
                                <div class="alert alert-warning"><?=$_msg?></div>
                            <?php endforeach;?>

                            <?php foreach($_success as $_msg): ?>
                                <div class="alert alert-success"><?=$_msg?></div>
                            <?php endforeach;?>

                            <?php if (count($_error)) : ?>
                                <div class="alert alert-danger">
                                <?php foreach($_error as $_msg): ?>
                                    <?=$_msg?><br />
                                <?php endforeach;?>
                                </div>
                            <?php endif; ?>

                            <?php foreach($_info as $_msg): ?>
                                <div class="alert alert-info"><?=$_msg?></div>
                            <?php endforeach;?>
                        </div><!-- end col-xs-12 -->
                    </div><!-- end .row -->

                    <?php foreach($_content as $_view): ?>
                        <?php include $_view;?>
                    <?php endforeach; ?>
                </div><!-- end container -->
            </div><!-- end main-content -->
        </div><!-- end main-container -->

        <?php /*if (isset($widgets['sidebar'])) : ?>
        <aside class="sidebar-right visible">
            <button type="button" id="toggle-menu">
                <i class="fa fa-calendar fa-lg"></i>
            </button>

            <?php foreach ($widgets['sidebar'] as $wds) : ?>
                <?php echo $wds; ?>
            <?php endforeach; ?>

            <?php $disabled = (isset($_disabled_add) && $_disabled_add) ? 'disabled' : ''; ?>
            <?php if ($this->user->has_permission('edit_posts')) : ?>
                <p class="text-center"><a href="<?php echo base_url(); ?>reminders/add" class="btn btn-blue <?php echo $disabled; ?>"><?php echo $this->lang->line('cms_general_title_add_reminder'); ?></a></p>
            <?php endif; ?>
        </aside>
        <?php endif; */ ?>

    <?php else : ?>
        <div class="wrapper-extranet">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <?php foreach($_content as $_view): ?>
                            <?php include $_view;?>
                        <?php endforeach; ?>
                    </div><!-- end col-xs-12 -->
                </div><!-- end row -->
            </div><!-- end container -->
        </div><!-- end wrapper-extranet -->
    <?php endif; ?>

        <!-- Load Javascript -->
        <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?php echo base_url(); ?>assets/scripts/libraries/jquery/jquery-1.11.0.min.js"><\/script>')</script>
        <script> var _root_ = '<?php echo base_url(); ?>'</script>
        <script type="text/javascript" src="http://fgnass.github.io/spin.js/spin.min.js"></script>
        <?php echo $_js; ?>
    </body>
</html>