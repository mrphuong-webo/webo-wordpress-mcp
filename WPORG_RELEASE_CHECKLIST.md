# WEBO WordPress MCP – WordPress.org Release Checklist

Checklist này bám theo:
- Detailed Plugin Guidelines
- Plugin Developer FAQ

## 1) Version + release consistency (Guideline #15)
- [ ] `webo-wordpress-mcp.php` `Version` đã tăng
- [ ] `readme.txt` `Stable tag` khớp đúng version release
- [ ] `readme.txt` có mục changelog cho version mới
- [ ] Code release (không chỉ readme) thì luôn bump version

## 2) Plugin metadata + naming
- [ ] Header đủ: `License`, `License URI`, `Requires at least`, `Requires PHP`, `Text Domain`
- [ ] `Tested up to` không vượt quá bản WP hiện tại/RC hiện tại
- [ ] Slug tránh trademark ở đầu tên nếu không phải official owner
- [ ] Không dùng slug gây nhầm lẫn thương hiệu, không nhồi từ khóa

## 3) License + third-party compliance (Guideline #1, #2)
- [ ] Toàn bộ code/assets/libs tương thích GPL
- [ ] Vendor/libs bên thứ ba có license rõ ràng và hợp lệ
- [ ] Terms của API/service ngoài đã được rà soát và có thể tuân thủ

## 4) Readme quality (Guideline #12 + FAQ)
- [ ] `readme.txt` đầy đủ: Description, Installation, FAQ, Changelog, Upgrade Notice
- [ ] Không spam tags/keyword stuffing (giữ tags gọn, thực sự liên quan)
- [ ] Không dùng affiliate links mập mờ hoặc cloaked redirect
- [ ] Nếu dùng external service, mô tả rõ service + link Terms/Privacy

## 5) Privacy + tracking (Guideline #7)
- [ ] Không gửi dữ liệu ra ngoài khi chưa có consent rõ ràng
- [ ] Nếu có telemetry/remote checks: có opt-in minh bạch
- [ ] Không offload JS/CSS/images ra ngoài nếu không cần cho service
- [ ] Tài liệu nêu rõ dữ liệu nào được gửi và mục đích sử dụng

## 6) Remote code / external execution (Guideline #8)
- [ ] Không tải/execute mã thực thi từ server ngoài theo kiểu cập nhật ngầm
- [ ] Không cài plugin/theme/add-on từ nguồn ngoài WordPress.org
- [ ] Không dùng iframe admin để thay thế API nội bộ
- [ ] JS/CSS không liên quan service được bundle local

## 7) UX/admin behavior (Guideline #10, #11)
- [ ] Không chèn "Powered by" ngoài frontend nếu chưa opt-in
- [ ] Không hijack admin dashboard (nags/ads phải tối giản, dismissible)
- [ ] Error/notice có hướng xử lý rõ và tự biến mất khi đã resolve

## 8) Core libraries + code readability (Guideline #4, #13)
- [ ] Không bundle lại thư viện mặc định WP khi đã có sẵn
- [ ] Code phải human-readable (không obfuscate)
- [ ] Nếu có file minified thì có nguồn non-minified hoặc link source rõ ràng

## 9) Security hardening
- [ ] Không còn debug logs/credentials/test secrets trong repo
- [ ] Nonce/capability/sanitize/escape đã rà soát
- [ ] Không còn endpoint public không cần thiết
- [ ] MCP flow test qua HMAC: `initialize` -> `tools/list` -> `tools/call`

## 10) Package hygiene (FAQ: submission/package)
- [ ] File zip production-ready, không chứa test/dev tooling không cần thiết
- [ ] Không nhúng file nén lồng nhau (`.zip`, archives) trong gói phát hành
- [ ] Kích thước gói hợp lý (tránh kéo full dev trees nếu không cần)
- [ ] `.distignore` đã bao phủ đúng

## 11) SVN/WordPress.org release operations (FAQ)
- [ ] `trunk/` chứa plugin chạy được ngay, không đặt plugin root trong subfolder sai
- [ ] Tag SVN đặt theo version number (ví dụ `1.1.1`)
- [ ] Không commit dồn dập kiểu spam update
- [ ] Asset WP.org (banner/icon/screenshot) đặt đúng ở thư mục assets SVN

## 12) Service/SaaS boundaries (Guideline #5, #6)
- [ ] Plugin không phải trialware khóa tính năng cốt lõi theo quota/time
- [ ] Nếu là service plugin: service có giá trị thực, mô tả rõ trong readme
- [ ] Không biến plugin thành storefront thuần bán hàng không có chức năng plugin thực chất

## 13) Final pre-submit gate
- [ ] Kích hoạt sạch trên site mới (WP_DEBUG bật, không warning/error)
- [ ] Test cài đặt/update từ dashboard
- [ ] Test ít nhất 1 luồng MCP end-to-end thành công
- [ ] Đối chiếu `WPORG_RELEASE_CHECKLIST.md` đạt 100% trước khi submit/tag
