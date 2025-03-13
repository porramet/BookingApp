<!-- Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content profile-card">
            <!-- Modal Body -->
            <!-- ตรวจสอบว่า Auth::user() มีค่าและไม่เป็น null -->
        <div class="modal-body text-center">
            <!-- Avatar -->
            <img 
                src="{{ Auth::check() && Auth::user()->avatar_url ? Auth::user()->avatar_url : asset('images/profile-avatar.png') }}" 
                alt="User Avatar"
                class="profile-avatar"
            >
            <!-- User Name -->
            <h3 class="profile-name">
                {{ Auth::check() ? Auth::user()->name : 'Guest' }}
            </h3>

            <!-- User Information -->
            <div class="profile-info">
                <p>
                    <strong>Email:</strong> {{ Auth::check() ? Auth::user()->email : 'Not logged in' }}
                    <a href="#" class="edit-icon">
                        <i class="fas fa-edit"></i>
                    </a>
                </p>
                <p>
                    <strong>Phone:</strong> {{ Auth::check() && Auth::user()->phone ? Auth::user()->phone : 'ยังไม่ได้เพิ่มข้อมูล' }}
                    <a href="#" class="edit-icon">
                        <i class="fas fa-edit"></i>
                    </a>
                </p>
                <p>
                    <strong>Address:</strong> {{ Auth::check() && Auth::user()->address ? Auth::user()->address : 'ยังไม่ได้เพิ่มข้อมูล' }}
                    <a href="#" class="edit-icon">
                        <i class="fas fa-edit"></i>
                    </a>
                </p>
                <p>
                    <strong>Date of Birth:</strong> {{ Auth::check() && Auth::user()->dob ? Auth::user()->dob : 'ยังไม่ได้เพิ่มข้อมูล' }}
                    <a href="#" class="edit-icon">
                        <i class="fas fa-edit"></i>
                    </a>
                </p>
                <!-- Change Password -->
                <p>
                    <strong>Change Password:</strong>
                    <a href="#" class="edit-icon" data-toggle="modal" data-target="#changePasswordModal">
                        <i class="fas fa-lock"></i> แก้ไขรหัสผ่าน
                    </a>
                </p>
            </div>
        </div>


            <!-- Modal Footer -->
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">เปลี่ยนรหัสผ่าน</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Change Password Form -->
                <form action="{{ route('user.changePassword') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="currentPassword">รหัสผ่านเดิม</label>
                        <input type="password" class="form-control" id="currentPassword" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="newPassword">รหัสผ่านใหม่</label>
                        <input type="password" class="form-control" id="newPassword" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">ยืนยันรหัสผ่านใหม่</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <button type="submit" class="btn btn-primary">เปลี่ยนรหัสผ่าน</button>
            </div>
                </form>
        </div>
    </div>
</div>

<!-- CSS -->
<style>
/* การ์ดโปรไฟล์ */
.profile-card {
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    background-color: #fff;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    max-width: 400px;
    margin: 0 auto;
}

/* อวาตาร์ */
.profile-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin: 0 auto 15px auto;
    border: 4px solid #007bff;
    object-fit: cover;
    display: block;
}

/* ชื่อผู้ใช้ */
.profile-name {
    font-size: 1.8rem;
    font-weight: bold;
    margin-bottom: 15px;
    color: #333;
}

/* ข้อมูลผู้ใช้ */
.profile-info {
    text-align: left;
    margin-top: 10px;
}

.profile-info p {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    font-size: 1rem;
}

.profile-info p strong {
    flex: 1;
    margin-right: 10px;
}

/* ไอคอนแก้ไข */
.edit-icon {
    margin-left: 10px;
    color: #007bff;
    cursor: pointer;
    font-size: 1.2rem;
}

.edit-icon:hover {
    color: #0056b3;
}

/* Footer ของ Modal */
.modal-footer {
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
