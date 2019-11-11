<?php
$this->load->view('layout/header');
$this->load->view('layout/topmenu');

function truncate($str, $len) {
    $tail = max(0, $len - 10);
    $trunk = substr($str, 0, $tail);
    $trunk .= strrev(preg_replace('~^..+?[\s,:]\b|^...~', '...', strrev(substr($str, $tail, $len - $tail))));
    return $trunk;
}
?>
<!-- ================== BEGIN PAGE CSS STYLE ================== -->

<link href="<?php echo base_url(); ?>assets/plugins/isotope/isotope.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>assets/plugins/lightbox/css/lightbox.css" rel="stylesheet" />

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="<?php echo base_url(); ?>assets/plugins/isotope/jquery.isotope.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/lightbox/js/lightbox-2.6.min.js"></script>

<link href="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/css/jquery.fileupload.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>assets/plugins/jquery-file-upload/css/jquery.fileupload-ui.css" rel="stylesheet" />


<style>
    .gallery .image img {
        width: 100%;
        height: auto;
        -webkit-border-radius: 3px 3px 0 0;
        -moz-border-radius: 3px 3px 0 0;
        border-radius: 3px 3px 0 0;
    }

    a.tag_style {
        padding: 2px 4px;
        background: black;
        color: white;
        border-radius: 6px;
        font-size: 10px;
    }

    .gallery .desc {
        margin-top: 12px;
    }
</style>

<!-- begin #content -->
<div id="content" class="content"  ng-controller="songDataController">
    <!-- begin breadcrumb -->
    <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">Home</a></li>
        <li class="active">News & Events</li>
    </ol>
    <!-- end breadcrumb -->
    <!-- begin page-header -->
    <h1 class="page-header">News & Events <small></small></h1>
    <!-- end page-header -->

    <div id="gallery" class="gallery row">
        <div class="col-md-3">
            <div class="panel  panel-inverse">
                <div class="list-group">
                    <?php
                    foreach ($songlatters as $lkey => $lvalue) {
                        ?>
                        <a href="<?php echo site_url("Songs/songs/" . $lvalue->id . "/0") ?>" class="list-group-item <?php echo $lvalue->id == $category_id ? 'active' : ''; ?>">
                            <?php echo $lvalue->title; ?>
                        </a>
                        <?php
                    }
                    ?>

                </div>
            </div>
        </div>
        <div class="col-md-1 ">
            <div class="panel  panel-inverse">
                <div class="list-group">
                    <?php
                    foreach ($songindex as $ikey => $ivalue) {
                        ?>
                        <a href="<?php echo site_url("Songs/songs/" . $category_id . "/" . $ivalue->id) ?>" class="list-group-item <?php echo $ivalue->id == $index_id ? 'active' : ''; ?>">
                            <?php echo $ivalue->title; ?>
                        </a>
                        <?php
                    }
                    ?>

                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="col-md-12 panel  panel-inverse">
                <div class="list-group">

                    <li href="#" class="list-group-item " ng-click="detailSong(song)" ng-repeat="song in resultData.songsList" style="cursor: pointer">
                        {{song.title}}
                    </li>


                </div>
            </div>
        </div>

        <div class="col-md-4 panel  panel-inverse">
            <div class="panel-body">
                <form action="#" method="POST" enctype="multipart/form-data">
                    <fieldset>
                        <legend>Add New Or Event</legend>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Title</label>
                            <input type="text" class="form-control" name="title"  placeholder="Enter Title Here">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Description</label>
                            <textarea class="form-control" name="description"  placeholder="Type Description Here" rows="10"></textarea>
                        </div>

                        <div class="" style="margin-bottom: 20px;">

                            <div class="btn-group" role="group" aria-label="..." style="float:left;margin-right: 10px;">
                                <span class="btn btn-success col fileinput-button" ">
                                    <i class="fa fa-plus"></i>
                                    <span>Add files...</span>
                                    <input type="file" name="file"  file-model="filemodel" accept="image/*,.pdf">
                                </span>
                            </div>


                            <span style="font-size: 10px;">  Attach File From Here (PDF, JPG, PNG Allowed)</span>

                            <h2 style="    font-size: 12px;">{{filemodel.name}}</h2>
                            <input type="hidden" name="file_real_name" value="{{filemodel.name}}"/>

                        </div>


                        <button type="submit" name="submit_data" class="btn btn-sm btn-primary m-r-5"><i class="fa fa-save"></i> Add Now</button>
                        <button type="button"  class="btn btn-sm btn-default" ><i class="fa fa-times"></i> Cancel</button>
                    </fieldset>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="panel panel-inverse">

                <div class="panel-body">

                    <ul class="media-list media-list-with-divider">

                        <li class="media media-sm" ng-repeat="data in resultData.list">
                            <a class="media-left" href="javascript:;">
                                <img src="<?php echo base_url(); ?>assets/svgicon/newspaper.svg" alt="" class="media-object rounded-corner">
                            </a>
                            <div class="media-body">
                                <h4 class="media-heading"> {{data.title}}</h4>
                                <p>{{data.description}}</p>
                                <p><a href="<?php echo base_url(); ?>assets/schoolfiles/{{data.attachment}}" target="_blank" class="btn btn-sm btn-success m-r-5" ng-if='data.attachment'  ng-click="downloadFile(data)">
                                        <i class="icon fa fa-paperclip" style="color: {{resultData.textcolor}};"></i> Attachment
                                    </a>
                                    <button class="btn btn-sm btn-inverse m-r-5" ng-click="detailPost(data)" ><i class="fa fa-edit"></i> Edit</button>
                                    <button class="btn btn-sm btn-danger" ng-click="deleteDataSingle(data.id)"><i class="fa fa-trash"></i> Delete</button>
                                    <span class="pull-right"> <i class="fa fa-clock-o"></i>  {{data.datetime}}</span>
                                </p>
                            </div>
                        </li>

                    </ul>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">{{selected.title}}</h4>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" enctype="multipart/form-data">
                        <fieldset>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Title</label>
                                <input type="text" class="form-control" name="title"  placeholder="Enter Title Here" value="{{selected.title}}">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Lyrics</label>
                                <textarea class="form-control" name="description"  placeholder="Type Description Here" rows="20" value="{{selected.lyrics}}"></textarea>
                            </div>

                          
                            <br/>
                            <input type="hidden" name="table_id" value="{{selected.id}}">
                            <button type="submit" name="update_data" class="btn btn-sm btn-primary m-r-5"><i class="fa fa-save"></i> Add Now</button>
                            <button type="button" data-dismiss="modal"  class="btn btn-sm btn-default" ><i class="fa fa-times"></i> Cancel</button>
                        </fieldset>
                    </form>
                </div>

            </div>
        </div>
    </div>

</div>
<!-- end #content -->


<?php
$this->load->view('layout/footer');
?>
<script>
    var gbltablename = "";
    var gblurl = "<?php echo site_url("MobileApi/songList/" . $index_id) ?>";
    var deleteurl = "";
</script>

<script src="<?php echo base_url(); ?>assets/angular/requestData.js"></script>
