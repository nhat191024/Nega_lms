import 'package:nega_lms/utils/imports.dart';

class ClassDetailBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<ClassDetailController>(() => ClassDetailController());
  }
}
