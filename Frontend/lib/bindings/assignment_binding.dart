import 'package:nega_lms/utils/imports.dart';

class AssignmentBinding extends Bindings {
  @override
  void dependencies() {
    Get.lazyPut<AssignmentController>(() => AssignmentController());
  }
}