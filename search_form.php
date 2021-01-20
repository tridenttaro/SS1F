<!-- 検索機能のHTML部分 -->

<!-- 検索機能。 -->
<form method="post" action="">
    <div class="container-fluid">
        <!-- キーワード検索 -->
        <div class="form-group">
            <div class="row">
            <div class="col-10">
        <input type=search name="search" value="<?php if(isset($_SESSION['word'])) {print $_SESSION['word'];}?>"
                id="search" pattern="\S|\S.*?\S" placeholder="<?php if(isset($ph_text)) { print $ph_text;} ?>"class="form-control col-form-label-lg" ></div>
        <!-- 決定ボタン -->
                <input type="submit" value="検索" >
                </div>
        </div>
        
        <!-- 折り畳み展開設定 -->
        <div onclick="obj=document.getElementById('open').style; obj.display=(obj.display=='none')?'block':'none';">
            <a style="cursor:pointer; background-color:white; border:1px solid;">詳細設定</a>
        </div>
        <!-- 折り畳まれ部分 -->
        <div id="open" style="display:none;clear:both;background-color:silver;">
            <!-- 検索対象 -->
            <div>
                検索対象<br>
                <label><input type=radio name="search_cat" value="title" checked>タイトル検索</label>
                <label><input type=radio name="search_cat" value="text">本文検索</label><br><br>
            </div>
            <!-- キーワードの検索範囲 -->
            <div>
                キーワード<br>
                <label><input type=radio name="search_method" value="and" checked>すべて含む</label>
                <label><input type=radio name="search_method" value="or">少なくとも1つを含む</label><br><br>
            </div>
            <!-- 検索件数 -->
            <div>
                表示件数:
                <select name="page_num">
                    <option value= 2 <?php if(isset($_SESSION['page_num']) && $_SESSION['page_num'] == 2) {print 'selected';} ?>>
                        2件(テスト用)
                    </option>
                    <option value= 5 <?php if(isset($_SESSION['page_num']) && $_SESSION['page_num'] == 5) {print 'selected';} ?>>
                        5件
                    </option>
                    <option value= 10 <?php if(isset($_SESSION['page_num']) && $_SESSION['page_num'] == 10) {print 'selected';} ?>>
                        10件
                    </option>
                </select>
            </div>
        </div>
        <!-- // 折り畳まれ部分 -->
        <br>
    </div>
    <!-- 検索結果の順番 -->
    <div>
        <select name="search_sort" onchange="submit(this.form)">
            <option value="DESC" <?php if(isset($_SESSION['search_sort']) && $_SESSION['search_sort'] == 'DESC') {print 'selected';} ?>>
                新しい順
            </option>
            <option value="ASC" <?php if(isset($_SESSION['search_sort']) && $_SESSION['search_sort'] == 'ASC') {print 'selected';} ?>>
                古い順
            </option>
        </select> 
    </div>
</form>
<script type="text/javascript" src="js/jquery-3.5.1.js"></script>
  <script type="text/javascript" src="js/bootstrap.bundle.js"></script>
