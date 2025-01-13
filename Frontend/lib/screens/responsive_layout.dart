import 'package:nega_lms/utils/imports.dart';

class ResponsiveLayoutController extends GetxController {
  final RxBool isMobile = true.obs;
  
  void updateLayout(BoxConstraints constraints) {
    isMobile.value = constraints.maxWidth < 768;
  }
}

class ResponsiveLayout extends StatelessWidget {
  final Widget mobile;
  final Widget desktop;
  final controller = Get.put(ResponsiveLayoutController());

  ResponsiveLayout({super.key, required this.mobile, required this.desktop});

  @override
  Widget build(BuildContext context) {
    return LayoutBuilder(
      builder: (context, constraints) {
        controller.updateLayout(constraints);
        return Obx(() => controller.isMobile.value ? mobile : desktop);
      },
    );
  }
}
