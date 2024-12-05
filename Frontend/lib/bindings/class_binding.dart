import 'package:nega_lms/utils/imports.dart';

class ClassBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<ClassController>(() => ClassController());
  }
}