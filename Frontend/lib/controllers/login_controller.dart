import 'package:nega_lms/utils/imports.dart';
// import 'package:http/http.dart' as http;

class LoginController extends GetxController {
  final TextEditingController username = TextEditingController();
  final RxBool isUsernameError = false.obs;
  final RxString usernameErrorText = "".obs;
  final TextEditingController password = TextEditingController();
  final RxBool isPasswordError = false.obs;
  final RxBool isObscureText = true.obs;
  final RxString passwordErrorText = "".obs;

  final RxBool isButtonLoading = false.obs;

  void validate() {
    if (username.text.isEmpty) {
      isUsernameError.value = true;
      usernameErrorText.value = "Vui lòng nhập tài khoản hoặc email";
    } else {
      isUsernameError.value = false;
      usernameErrorText.value = "";
    }

    if (password.text.isEmpty) {
      isPasswordError.value = true;
      passwordErrorText.value = "Vui lòng nhập mật khẩu";
    } else {
      isPasswordError.value = false;
      passwordErrorText.value = "";
    }
  }

  login() async {
    validate();
    if (!isUsernameError.value && !isPasswordError.value) {
      var url = Uri.parse("${Api.server}login");
      try {
        var response = await post(url, body: {
          "login": username.text,
          "password": password.text,
        });
        var data = jsonDecode(response.body);
        if (response.statusCode == 200) {
          storeToken(data["token"]);
          Get.offAllNamed(Routes.homePage);
        } else if (response.statusCode == 401) {
          Get.snackbar("Lỗi", data["message"]);
        }
        isButtonLoading.value = true;
      } finally {
        isButtonLoading.value = false;
      }
    }
  }

  storeToken(String token) async {
    if (StorageService.checkData(key: LocalStorageKeys.token)) {
      StorageService.removeData(key: LocalStorageKeys.token);
    }

    StorageService.writeStringData(
      key: LocalStorageKeys.token,
      value: token,
    );
  }
}
