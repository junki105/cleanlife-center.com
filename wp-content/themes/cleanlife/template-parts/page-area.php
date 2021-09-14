<?php
/* 
Template Name: 対応エリア
*/
get_header();
 ?>

    <section class="page-title">
        <div class="content">
            <div class="current-page">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">トップ</a>
                > 対応エリア
            </div>
            <div class="title">
                <img src="<?php echo get_template_directory_uri()?>/assets/image/title-logo.png" alt="">
                <h1>対応エリア</h1>
                <div class="subtitle"><div>AREA</div></div>
            </div>
        </div>
    </section>
    <div class="l-wrap">
        <main class="l-main area">
            <section class="area-section">
                <div class="area-box-container">
                    <div class="area-box area-show">
                        <div class="area-q" onclick="areaClick(this);">北海道・東北対応エリア
                            <div class="area-q-icon">
                                <div><div></div></div>
                            </div>
                        </div>
                        <div class="area-a">
                            <div>【北海道】</div>
                            石狩市｜恵庭市｜江別市｜小樽市｜北広島市｜札幌市
                            <div>【宮城県】</div>
                            岩沼市｜塩竈市｜仙台市｜多賀城市｜名取市
                        </div>
                    </div>
                    <div class="area-box">
                        <div class="area-q" onclick="areaClick(this);">関東対応エリア
                            <div class="area-q-icon">
                                <div><div></div></div>
                            </div>
                        </div>
                        <div class="area-a">
                            <div>【茨城県】</div>
                            石岡市｜潮来市｜稲敷市｜牛久市｜笠間市｜鹿嶋市｜かすみがうら市｜北茨城市｜古河市｜下妻市｜高萩市｜筑西市｜つくば市｜土浦市｜取手市｜那珂市｜坂東市｜常陸太田市｜常陸大宮市｜日立市｜ひたちなか市｜水戸市｜守谷市｜結城市｜龍ケ崎市
                            <div>【栃木県】</div>
                            足利市｜宇都宮市｜小山市｜鹿沼市｜佐野市｜栃木市｜真岡市
                            <div>【群馬県】</div>
                            伊勢崎市｜太田市｜桐生市｜高崎市｜館林市｜藤岡市｜前橋市
                            <div>【埼玉県】</div>
                            上尾市｜朝霞市｜入間郡越生町｜入間郡三芳町｜入間郡毛呂山町｜入間市｜大里郡寄居町｜桶川市｜春日部市｜加須市｜川口市｜川越市｜北足立郡伊奈町｜北葛飾郡杉戸町｜北葛飾郡松伏町｜北本市｜行田市｜久喜市｜熊谷市｜鴻巣市｜越谷市｜児玉郡神川町｜児玉郡上里町｜児玉郡美里町｜さいたま市岩槻区｜さいたま市浦和区｜さいたま市大宮区｜さいたま市北区｜さいたま市桜区｜さいたま市中央区｜さいたま市西区｜さいたま市緑区｜さいたま市南区｜さいたま市見沼区｜坂戸市｜幸手市｜狭山市｜志木市｜白岡市｜草加市｜秩父郡小鹿野町｜秩父郡長瀞町｜秩父郡東秩父村｜秩父郡皆野町｜秩父郡横瀬町｜秩父市｜鶴ヶ島市｜所沢市｜戸田市｜新座市｜蓮田市｜羽生市｜飯能市｜東松山市｜比企郡小川町｜比企郡川島町｜比企郡ときがわ町｜比企郡滑川町｜比企郡鳩山町｜比企郡吉見町｜比企郡嵐山町｜日高市｜深谷市｜富士見市｜ふじみ野市｜本庄市｜三郷市｜南埼玉郡宮代町｜八潮市｜吉川市｜和光市｜蕨市
                            <div>【千葉県】</div>
                            我孫子市｜市川市｜市原市｜印西市｜浦安市｜柏市｜勝浦市｜木更津市｜君津市｜佐倉市｜白井市｜袖ケ浦市｜館山市｜千葉市稲毛区｜千葉市中央区｜千葉市花見川区｜千葉市緑区｜千葉市美浜区｜千葉市若葉区｜東金市｜富里市｜流山市｜習志野市｜成田市｜野田市｜富津市｜船橋市｜松戸市｜茂原市｜八街市｜八千代市｜四街道市
                            <div>【東京都】</div>
                            昭島市｜あきる野市｜足立区｜荒川区｜板橋区｜稲城市｜江戸川区｜青梅市｜大田区｜葛飾区｜北区｜清瀬市｜国立市｜江東区｜小金井市｜国分寺市｜小平市｜狛江市｜品川区｜渋谷区｜新宿区｜杉並区｜墨田区｜世田谷区｜台東区｜立川市｜多摩市｜中央区｜調布市｜千代田区｜中野区｜西多摩郡奥多摩町｜西多摩郡日の出町｜西多摩郡檜原村｜西多摩郡瑞穂町｜西東京市｜練馬区｜八王子市｜羽村市｜東久留米市｜東村山市｜東大和市｜日野市｜府中市｜福生市｜文京区｜町田市｜三鷹市｜港区｜武蔵野市｜武蔵村山市｜目黒区
                            <div>【神奈川県】</div>
                            愛甲郡愛川町｜愛甲郡清川村｜足柄上郡大井町｜足柄上郡開成町｜足柄上郡中井町｜足柄上郡松田町｜足柄上郡山北町｜足柄下郡箱根町｜足柄下郡真鶴町｜足柄下郡湯河原町｜厚木市｜綾瀬市｜伊勢原市｜海老名市｜小田原市｜鎌倉市｜川崎市麻生区｜川崎市川崎区｜川崎市幸区｜川崎市高津区｜川崎市多摩区｜川崎市中原区｜川崎市宮前区｜高座郡寒川町｜相模原市中央区｜横浜市緑区｜横浜市南区｜座間市｜逗子市｜茅ヶ崎市｜中郡大磯町｜中郡二宮町｜秦野市｜平塚市｜藤沢市｜三浦郡葉山町｜三浦市｜南足柄市｜大和市｜横須賀市｜横浜市青葉区｜横浜市旭区｜横浜市泉区｜横浜市磯子区｜横浜市神奈川区｜横浜市金沢区｜横浜市港南区｜横浜市港北区｜横浜市栄区｜横浜市瀬谷区｜横浜市都筑区｜横浜市鶴見区｜横浜市戸塚区｜横浜市中区｜横浜市西区｜横浜市保土ケ谷区
                        </div>
                    </div>
                    <div class="area-box">
                        <div class="area-q" onclick="areaClick(this);">東海対応エリア
                            <div class="area-q-icon">
                                <div><div></div></div>
                            </div>
                        </div>
                        <div class="area-a">
                            <div>【愛知県】</div>
                            愛西市｜愛知郡東郷町｜海部郡大治町｜海部郡蟹江町｜海部郡飛島村｜あま市｜安城市｜一宮市｜稲沢市｜犬山市｜岩倉市｜大府市｜岡崎市｜尾張旭市｜春日井市｜蒲郡市｜刈谷市｜北設楽郡設楽町｜北設楽郡東栄町｜北設楽郡豊根村｜北名古屋市｜清須市｜江南市｜小牧市｜新城市｜瀬戸市｜高浜市｜田原市｜知多郡阿久比町｜知多郡武豊町｜知多郡東浦町｜知多郡南知多町｜知多郡美浜町｜知多市｜知立市｜津島市｜東海市｜常滑市｜豊明市｜豊川市｜豊田市｜豊橋市｜名古屋市熱田区｜名古屋市北区｜名古屋市昭和区｜名古屋市千種区｜名古屋市天白区｜名古屋市中川区｜名古屋市中区｜名古屋市中村区｜名古屋市西区｜名古屋市東区｜名古屋市瑞穂区｜名古屋市緑区｜名古屋市港区｜名古屋市南区｜名古屋市名東区｜名古屋市守山区｜西尾市｜西春日井郡豊山町｜日進市｜丹羽郡大口町｜丹羽郡扶桑町｜額田郡幸田町｜半田市｜碧南市
                            <div>【岐阜県】</div>
                            揖斐郡池田町｜揖斐郡揖斐川町｜揖斐郡大野町｜恵那市｜大垣市｜大野郡白川村｜海津市｜各務原市｜可児郡御嵩町｜可児市｜加茂郡川辺町｜加茂郡坂祝町｜加茂郡白川町｜加茂郡富加町｜加茂郡東白川村｜加茂郡七宗町｜加茂郡八百津町｜岐阜市｜下呂市｜関市｜多治見市｜土岐市｜中津川市｜羽島郡笠松町｜羽島郡岐南町｜羽島市｜瑞浪市｜瑞穂市｜美濃加茂市｜美濃市｜本巣郡北方町｜本巣市｜山県市｜養老郡養老町
                            <div>【静岡県】</div>
                            磐田市｜御前崎市｜掛川市｜菊川市｜静岡市葵区｜静岡市清水区｜静岡市駿河区｜島田市｜浜松市北区｜浜松市天竜区｜浜松市中区｜浜松市西区｜浜松市浜北区｜浜松市東区｜浜松市南区｜袋井市｜藤枝市｜焼津市
                            <div>【三重県】</div>
                            伊勢市｜員弁郡東員町｜いなべ市｜亀山市｜北牟婁郡紀北町｜桑名郡木曽岬町｜桑名市｜鈴鹿市｜多気郡大台町｜多気郡多気町｜多気郡明和町｜津市｜名張市｜松阪市｜三重郡朝日町｜三重郡川越町｜三重郡菰野町｜南牟婁郡紀宝町｜南牟婁郡御浜町｜四日市市｜度会郡大紀町｜度会郡玉城町｜度会郡南伊勢町｜度会郡度会町
                        </div>
                    </div>
                    <div class="area-box">
                        <div class="area-q" onclick="areaClick(this);">関西対応エリア
                            <div class="area-q-icon">
                                <div><div></div></div>
                            </div>
                        </div>
                        <div class="area-a">
                            <div>【大阪府】</div>
                            池田市｜泉大津市｜泉佐野市｜和泉市｜茨木市｜大阪狭山市｜大阪市旭区｜大阪市阿倍野区｜大阪市生野区｜堺市北区｜大阪市此花区｜大阪市城東区｜大阪市住之江区｜大阪市住吉区｜大阪市大正区｜大阪市中央区｜大阪市鶴見区｜大阪市天王寺区｜大阪市浪速区｜堺市西区｜大阪市西成区｜大阪市西淀川区｜大阪市東住吉区｜大阪市東成区｜大阪市東淀川区｜大阪市平野区｜大阪市福島区｜大阪市港区｜大阪市都島区｜大阪市淀川区｜貝塚市｜柏原市｜交野市｜門真市｜河内長野市｜岸和田市｜堺市堺区｜堺市中区｜堺市東区｜堺市南区｜堺市美原区｜四條畷市｜吹田市｜摂津市｜泉南郡熊取町｜泉南郡田尻町｜泉南郡岬町｜泉南市｜泉北郡忠岡町｜大東市｜高石市｜高槻市｜豊中市｜豊能郡豊能町｜豊能郡能勢町｜富田林市｜寝屋川市｜羽曳野市｜阪南市｜東大阪市｜枚方市｜藤井寺市｜松原市｜三島郡島本町｜南河内郡河南町｜南河内郡太子町｜南河内郡千早赤阪村｜箕面市｜守口市｜八尾市
                            <div>【京都府】</div>
                            宇治市｜乙訓郡大山崎町｜亀岡市｜京田辺市｜京都市右京区｜京都市上京区｜京都市北区｜京都市左京区｜京都市下京区｜京都市中京区｜京都市西京区｜京都市東山区｜京都市伏見区｜京都市南区｜京都市山科区｜久世郡久御山町｜城陽市｜相楽郡笠置町｜相楽郡精華町｜相楽郡南山城村｜相楽郡和束町｜綴喜郡井手町｜綴喜郡宇治田原町｜向日市｜八幡市
                            <div>【兵庫県】</div>
                            相生市｜明石市｜芦屋市｜尼崎市｜淡路市｜伊丹市｜揖保郡太子町｜小野市｜加古川市｜加古郡稲美町｜加古郡播磨町｜加西市｜加東市｜川西市｜川辺郡猪名川町｜神戸市北区｜神戸市須磨区｜神戸市垂水区｜神戸市中央区｜神戸市長田区｜神戸市灘区｜神戸市西区｜神戸市東灘区｜神戸市兵庫区｜佐用郡佐用町｜三田市｜洲本市｜高砂市｜宝塚市｜西宮市｜西脇市｜姫路市｜三木市｜南あわじ市
                            <div>【滋賀県】</div>
                            近江八幡市｜大津市｜草津市｜甲賀市｜長浜市｜東近江市｜彦根市｜米原市｜守山市｜栗東市
                            <div>【奈良県】</div>
                            生駒郡安堵町｜生駒郡斑鳩町｜生駒郡三郷町｜生駒郡平群町｜生駒市｜宇陀郡曽爾村｜宇陀郡御杖村｜宇陀市｜香芝市｜橿原市｜葛城市｜北葛城郡王寺町｜北葛城郡河合町｜北葛城郡上牧町｜北葛城郡広陵町｜五條市｜御所市｜桜井市｜磯城郡川西町｜磯城郡田原本町｜磯城郡三宅町｜高市郡明日香村｜高市郡高取町｜天理市｜奈良市｜大和郡山市｜大和高田市｜山辺郡山添村｜吉野郡大淀町｜吉野郡上北山村｜吉野郡川上村｜吉野郡黒滝村｜吉野郡下市町｜吉野郡下北山村｜吉野郡天川村｜吉野郡十津川村｜吉野郡野迫川村｜吉野郡東吉野村｜吉野郡吉野町
                            <div>【和歌山県】</div>
                            橋本市｜和歌山市
                        </div>
                    </div>
                    <div class="area-box">
                        <div class="area-q" onclick="areaClick(this);">中国対応エリア
                            <div class="area-q-icon">
                                <div><div></div></div>
                            </div>
                        </div>
                        <div class="area-a">
                            <div>【岡山県】</div>
                            浅口郡里庄町｜浅口市｜井原市｜岡山市北区｜岡山市中区｜岡山市東区｜岡山市南区｜笠岡市｜倉敷市｜玉野市
                            <div>【山口県】</div>
                            阿武郡阿武町｜岩国市｜宇部市｜大島郡周防大島町｜玖珂郡和木町｜下松市｜熊毛郡上関町｜熊毛郡田布施町｜熊毛郡平生町｜山陽小野田市｜下関市｜周南市｜長門市｜萩市｜光市｜防府市｜美祢市｜柳井市｜山口市
                        </div>
                    </div>
                    <div class="area-box">
                        <div class="area-q" onclick="areaClick(this);">九州・沖縄対応エリア
                            <div class="area-q-icon">
                                <div><div></div></div>
                            </div>
                        </div>
                        <div class="area-a">
                            <div>【福岡県】</div>
                            朝倉郡筑前町｜朝倉郡東峰村｜朝倉市｜飯塚市｜糸島市｜うきは市｜大川市｜大野城市｜大牟田市｜小郡市｜遠賀郡芦屋町｜遠賀郡岡垣町｜遠賀郡遠賀町｜遠賀郡水巻町｜春日市｜糟屋郡宇美町｜糟屋郡粕屋町｜糟屋郡篠栗町｜糟屋郡志免町｜糟屋郡新宮町｜糟屋郡須惠町｜糟屋郡久山町｜嘉穂郡桂川町｜嘉麻市｜北九州市小倉北区｜北九州市小倉南区｜北九州市戸畑区｜北九州市門司区｜北九州市八幡西区｜北九州市八幡東区｜北九州市若松区｜鞍手郡鞍手町｜鞍手郡小竹町｜久留米市｜古賀市｜田川郡赤村｜田川郡糸田町｜田川郡大任町｜田川郡川崎町｜田川郡香春町｜田川郡添田町｜田川郡福智町｜田川市｜太宰府市｜筑後市｜筑紫野市｜築上郡上毛町｜築上郡築上町｜築上郡吉富町｜那珂川市｜中間市｜直方市｜福岡市早良区｜福岡市城南区｜福岡市中央区｜福岡市西区｜福岡市博多区｜福岡市東区｜福岡市南区｜福津市｜豊前市｜宗像市｜柳川市｜八女市｜行橋市
                            <div>【佐賀県】</div>
                            神埼郡吉野ヶ里町｜佐賀市
                        </div>
                    </div>
                </div>
            </section>
            <section class="ask-us">
                <div class="ask-us-img">
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/ranking-men.png" alt="">
                    <div class="ask-us-title"><div>水道<span>の</span>トラブル</div><span>なら</span>なんでも<br>
                        クリーンライフ<span>に</span>ご相談ください<div class="last-dot">。</div></div>
                </div>
                <div class="ask-us-text">水道のトラブルはなんでもご相談ください。水漏れやつまり、部品交換修理などクリーンライフでは水道に関わるあらゆるトラブルを解決いたします。漏水場所が判明しない場合でも水漏れ箇所の特定からご対応することが可能です。お困りごとがありましたら是非クリーンライフにご相談ください。</div>
            </section>
            <section class="coupon">
                <img src="<?php echo get_template_directory_uri()?>/assets/image/sub-coupon.jpg" alt="" class="coupon-back">
                <div class="coupon-content">
                    <div class="coupon-tel-container">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-off.png" alt="" class="coupon-off">
                        <div class="coupon-tel-container01">
                            <div class="coupon-title">お電話1本<span class="coupon-title-small">で</span><br><span class="coupon-title-dot">す</span><span class="coupon-title-dot">ぐ</span><span class="coupon-title-dot">に</span>駆けつけます!</div>
                            <div class="coupon-tel-main">
                                <div class="coupon-current">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/red-clock.png" alt="">
                                    <div class="coupon-current-time">
                                        <span  class="current-time-all">13：25</span>
                                        現在、お電話いただけましたら即日修理対応可能です！
                                    </div>
                                </div>
                                <div class="coupon-tel-main01">
                                    <div class="coupon-tel-text">
                                        24時間・365日対応
                                        <div>お見積無料</div>
                                    </div>
                                    <a href="tel:0120-423-152">
                                        <img src="<?php echo get_template_directory_uri()?>/assets/image/footer-tel.png" alt="">
                                        0120-423-152
                                    </a>
                                </div>
                            </div>
                            <div class="coupon-drop-container">
                                <div class="coupon-drop">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-drop.png" alt="">
                                    <div>出張見積<br>
                                        無料！</div>
                                </div>
                                <div class="coupon-drop">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-drop.png" alt="">
                                    <div>キャンセル<br>
                                        無料！</div>
                                </div>
                                <div class="coupon-drop">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-drop.png" alt="">
                                    <div>深夜料金<br>
                                        休日料金<br>
                                        一切なし！</div>
                                </div>
                                <div class="coupon-drop">
                                    <img src="<?php echo get_template_directory_uri()?>/assets/image/top/coupon-drop.png" alt="">
                                    <div>安心の<br>
                                        無料保証</div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- CTA ADD Start -->
                <div class="coupon-tel-main-sp" onclick="location.href='tel:0120-423-152';" style="cursor:pointer;">
                    <div class="coupon-tel-hand">
                        <span>ここをタップして今すぐお電話
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/tel-hand.png" alt="">
                        </span>
                    </div>
                    <div class="coupon-current">
                        <img src="<?php echo get_template_directory_uri()?>/assets/image/white-clock.png" alt="">
                        <div class="coupon-current-time">
                            <span  class="current-time-all">13：25</span>
                            現在、お電話いただけましたら即日修理対応可能です！
                        </div>
                    </div>
                    <div class="coupon-tel-main01">
                        <div class="coupon-tel-text">
                            24時間・365日対応
                            <div>お見積無料</div>
                        </div>
                        <a href="tel:0120-423-152">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/tel.png" alt="">
                            <span>0120-423-152</span>
                        </a>
                    </div>
                </div>
                <div class="coupon-pay-container">
                    <div class="coupon-payment">
                        <div class="coupon-pay-box">
                            <div>各種クレジットカード対応</div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/payment1.png" alt="">
                        </div>
                        <div class="coupon-pay-box">
                            <div>コンビニ後払い対応</div>
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/top/payment2.png" alt="">
                        </div>
                    </div>
                    <div class="coupon-line-container">
                        <a href="https://lin.ee/RqJ6Mk3" target="_blank" rel="noopener noreferrer" class="coupon-line">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/line.png" alt="">
                            <div>LINEで無料相談</div>
                            <div class="coupon-line-add">\最短<span>30秒</span>でご返信/</div>
                        </a>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>contact" class="coupon-mail">
                            <img src="<?php echo get_template_directory_uri()?>/assets/image/mail.png" alt="">
                            <div>メールで無料相談</div>
                            <div class="coupon-mail-add">\専門スタッフが<span>即対応</span>！/</div>
                        </a>
                    </div>
                </div>
                <!-- CTA ADD End -->
                    <img src="<?php echo get_template_directory_uri()?>/assets/image/woman.png" alt="" class="coupon-woman">
                </div>
            </section>
        </main>
        <?php get_sidebar();?>
    </div>
<?php get_footer();?>