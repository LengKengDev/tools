<div class="row">
    <div class="col-sm-12">
        <h4 class="text-center text-uppercase">Hướng dẫn</h4>
        <p>Để có thể sử dụng Jobcan autobot cần sử dụng một số thông tin cần thiết
            như Slack token, slack public channel id, dưới đây là hướng dẫn để có
            thể lấy được những thông tin cần thiết này.
            </p>
        <p class="text-center text-muted">Hãy Click vào từng bước để xem chi tiết.</p>
        <div id="accordion">
            <div class="card shadow border-0">
                <div class="card-header">
                    <a class="card-link" data-toggle="collapse" href="#collapseOne">
                        1. Liên kết jobcan với Slack
                    </a>
                </div>
                <div id="collapseOne" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                        <ul>
                            <li>Nếu đã từng liên kết thì có thể không cần tham gia channel này nhưng bắt buộc phải nhập một public channel id mà bạn đang tham gia vào ô custom channel bên dưới. Nếu bạn đăng ký tham gia channel Jobcan Autobot này thì phần đó sẽ không bắt buộc.</li>
                            <li>Đăng ký tham gia Slack channel bằng tài khoản @sun-asterisk.com tại
                                <a target="_blank" href="https://join.slack.com/t/jobcanautobot/signup">https://join.slack.com/t/jobcanautobot/signup</a>
                            </li>
                            <li>Sau khi có tài khoản slack thì bắt đầu liên kết bằng cách bấm vào đây để thêm Jobcan vào slack:
                                <a target="_blank" href="https://slack.com/oauth/authorize?scope=commands&amp;client_id=3164932209.141855791831"><img src="https://platform.slack-edge.com/img/add_to_slack.png" srcset="https://platform.slack-edge.com/img/add_to_slack.png 1x, https://platform.slack-edge.com/img/add_to_slack@2x.png 2x" alt="Add to Slack" width="139" height="40"><br><br></a></li>
                            <li>Khi cấp quyền thành công ở phần message trong một public room chat ở slack gõ <code>/jobcan_register_jbcid</code></li>
                            <li>Slack sẽ gửi lại 1 link, bạn bấm vào liên kết đó để kết nối tới jobcan (đăng nhập jobcan để cấp quyền thui à). Như vậy là đã liên kết thành công</li>
                        </ul>                    </div>
                </div>
            </div>
            <div class="card shadow border-0">
                <div class="card-header">
                    <a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo">
                        2. Lấy slack token
                    </a>
                </div>
                <div id="collapseTwo" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                        <ul>
                            <li>Truy cập đường dẫn <a target="_blank" href="https://api.slack.com/custom-integrations/legacy-tokens">https://api.slack.com/custom-integrations/legacy-tokens</a></li>
                            <li>Ở phần <b>Legacy token generator</b> có danh sách các workspace bạn tham gia, bên cạnh có nút <b>generate</b> để tạo token. Nếu bạn tham gia Jobcan Autobot thì tên workspace sẽ là Jobcan Autobot (tham khảo ảnh dưới).
                                <div class="text-center">
                                    <img src="https://i.imgur.com/Li3jkEs.png" alt="" class="img-fluid">
                                </div>
                            </li>
                            <li>Copy token điền vào ô token của phần đăng ký này.</li>
                        </ul>                    </div>
                </div>
            </div>
            <div class="card shadow border-0">
                <div class="card-header">
                    <a class="collapsed card-link" data-toggle="collapse" href="#collapseThree">
                        3. Cấu hình Slack channel và Chatwork notify
                    </a>
                </div>
                <div id="collapseThree" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                        <h6>1. Slack channel ID</h6>
                        <ul><li>Là phần trên URL của slack có chứa channel id

                                <div class="text-center">
                                    <img src="https://i.imgur.com/YvHce53.png" alt="" class="img-fluid">
                                </div></li></ul>
                        <h6>2. Thông báo đã 打刻 qua Chatwork ID</h6>
                        <ul>
                            <li>Chatwork khi ai TO mình sẽ có dạng <code>[To:2930830] HIEN(ヒエン）Nguyen Thiさん</code> lấy phần số và điền vào ô nhập liệu</li>
                        </ul>                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
