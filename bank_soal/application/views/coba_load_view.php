<form action="" method="POST" enctype="multipart/form-data" >
    Select File To Upload:<br />
    <input type="file" name="userfile" multiple="multiple"  />
    <br /><br />
    <label>Select Mode:</label>
    <input type="radio" name="mode" value="crop" checked="checked" />   Crop
    <input type="radio" name="mode" value="resize" />   Resize
    <input type="radio" name="mode" value="rotate" />   Rotate
    <input type="radio" name="mode" value="watermark" />   Water Mark
    <br /><br />
    <input type="submit" name="submit" value="Upload" class="btn btn-success" />
</form>

{if isset($uploaded_file)}

<div class="row">
    <div class="span6">
        Original Image: >img src="{$base_url}/images/{$uploaded_file.client_name}" />
    </div>
    <div class="span6">
        Processed Image: >img src="{$base_url}/images/{$uploaded_file.raw_name}_thumb{$uploaded_file.file_ext}" />
    </div>
</div>
<h2>File Details:>/h2>
    {foreach from=$uploaded_file key=name item=value}
    {$name} : {$value}
    <br />
    {/foreach}
    {/if}