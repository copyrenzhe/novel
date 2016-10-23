<div class="form-group">
    <label for="name" class="col-md-3 control-label">小说名</label>
    <div class="col-md-6">
        <input type="text" class="form-control" name="name" id="name" value="{{ $name }}" autofocus>
    </div>
</div>
<div class="form-group">
    <label for="author_id" class="col-md-3 control-label">小说作者</label>
    <div class="col-md-6">
        <input type="text" class="form-control" name="author_id" id="author_id" value="{{ $author_id }}" autofocus>
    </div>
</div>
<div class="form-group">
    <label for="type" class="col-md-3 control-label">类型</label>
    <div class="col-md-6">
        <select name="type" id="type" class="form-control">
            <option value="xuanhuan" {{ $type=='xuanhuan' ? 'seleced' : '' }}>玄幻</option>
            <option value="xiuzhen" {{ $type=='xiuzhen' ? 'seleced' : '' }}>修真</option>
            <option value="dushi" {{ $type=='dushi' ? 'seleced' : '' }}>都市</option>
            <option value="lishi" {{ $type=='lishi' ? 'seleced' : '' }}>历史</option>
            <option value="wangyou" {{ $type=='wangyou' ? 'seleced' : '' }}>网游</option>
            <option value="kehuan" {{ $type=='kehuan' ? 'seleced' : '' }}>科幻</option>
            <option value="other" {{ $type=='other' ? 'seleced' : '' }}>其他</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label for="cover" class="col-md-3 control-label">封面图片</label>
    <div class="col-md-6">
        <input type="text" class="form-control" name="cover" id="cover" value="{{ $cover }}" autofocus>
    </div>
</div>
<div class="form-group">
    <label for="chapter_num" class="col-md-3 control-label">章节数</label>
    <div class="col-md-6">
        <input type="text" class="form-control" name="chapter_num" id="chapter_num" value="{{ $chapter_num }}" autofocus>
    </div>
</div>
<div class="form-group">
    <label for="hot" class="col-md-3 control-label">热度</label>
    <div class="col-md-6">
        <input type="text" class="form-control" name="hot" id="hot" value="{{ $hot }}" autofocus>
    </div>
</div>
<div class="form-group">
    <label for="sort" class="col-md-3 control-label">排序</label>
    <div class="col-md-6">
        <input type="text" class="form-control" name="sort" id="sort" value="{{ $sort }}" autofocus>
    </div>
</div>
<div class="form-group">
    <label for="is_over" class="col-md-3 control-label">是否完结</label>
    <div class="col-md-6">
        <div class="checkbox-inline">
            <input type="radio" name="is_over" value="0" {{ $is_over ? '' : 'checked' }}>否</input>
        </div>
        <div class="checkbox-inline">
            <input type="radio" name="is_over" value="1" {{ $is_over ? 'checked' : '' }}>是</input>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="source" class="col-md-3 control-label">来源</label>
    <div class="col-md-6">
        <input type="text" class="form-control" name="source" id="source" value="{{ $source }}" autofocus>
    </div>
</div>
<div class="form-group">
    <label for="biquge_url" class="col-md-3 control-label">来源链接</label>
    <div class="col-md-6">
        <input type="text" class="form-control" name="biquge_url" id="biquge_url" value="{{ $biquge_url }}" autofocus>
    </div>
</div>
<div class="form-group">
    <label for="description" class="col-md-3 control-label">小说描述</label>
    <div class="col-md-6">
        <textarea name="description" class="form-control" rows="3">{{ $description }}</textarea>
    </div>
</div>

