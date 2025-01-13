import 'package:nega_lms/utils/imports.dart';

class CourseDetailScreen extends StatelessWidget {
  CourseDetailScreen({super.key});

  final controller = Get.find<CourseDetailController>();

  @override
  Widget build(BuildContext context) {
    return Obx(() {
      if (controller.isLoading.value) {
        return const Center(child: CircularProgressIndicator());
      }
      return Padding(
        padding: const EdgeInsets.fromLTRB(40, 20, 40, 0),
        child: Column(
          children: [
            _buildTopContainer(),
            // const SizedBox(width: 20),
            // Expanded(
            //   child: Column(
            //     children: [
            //       const SizedBox(height: 20),
            //       _postNewPost(),
            //     ],
            //   ),
            // ),
          ],
        ),
      );
    });
  }

  Widget _buildTopContainer() {
    return Container(
      width: Get.width,
      height: Get.height * 0.2,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: CustomColors.background,
        borderRadius: BorderRadius.circular(16),
      ),
      child: Align(
        alignment: Alignment.bottomLeft,
        child: Text(
          controller.courseName.value,
          style: const TextStyle(
            fontSize: 24,
            color: CustomColors.primary,
            fontFamily: FontStyleTextStrings.bold,
          ),
        ),
      ),
    );
  }
}
