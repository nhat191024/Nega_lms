import 'package:nega_lms/utils/imports.dart';

class ClassOverviewScreen extends StatelessWidget {
  ClassOverviewScreen({super.key});

  final controller = Get.find<ClassDetailController>();

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
            Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Column(
                  children: [
                    const SizedBox(height: 20),
                    _buildClassCodeContainer(),
                    const SizedBox(height: 20),
                    _buildEventContainer(),
                  ],
                ),
                const SizedBox(width: 20),
                Expanded(
                  child: Column(
                    children: [
                      const SizedBox(height: 20),
                      _postNewPost(),
                    ],
                  ),
                ),
              ],
            ),
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
          controller.className.value,
          style: const TextStyle(
            fontSize: 24,
            color: CustomColors.primary,
            fontFamily: FontStyleTextStrings.bold,
          ),
        ),
      ),
    );
  }

  Widget _buildClassCodeContainer() {
    return Container(
      width: Get.width * 0.15,
      padding: const EdgeInsets.fromLTRB(20, 10, 20, 10),
      decoration: BoxDecoration(
        color: CustomColors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(
          color: CustomColors.border,
        ),
      ),
      child: Column(
        children: [
          Row(
            children: [
              const Text(
                'Mã lớp',
                style: TextStyle(
                  fontSize: 18,
                  color: CustomColors.primaryText,
                  fontFamily: FontStyleTextStrings.medium,
                ),
              ),
              const Spacer(),
              IconButton(
                onPressed: () {},
                icon: const Icon(
                  Icons.more_vert_rounded,
                  color: CustomColors.primary,
                ),
              ),
            ],
          ),
          Row(
            children: [
              Text(
                controller.classCode.value.toString(),
                style: const TextStyle(
                  fontSize: 20,
                  color: CustomColors.primary,
                  fontFamily: FontStyleTextStrings.bold,
                ),
              ),
              IconButton(onPressed: () {}, icon: const Icon(Icons.fullscreen_rounded)),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildEventContainer() {
    return Container(
      width: Get.width * 0.15,
      height: Get.height * 0.2,
      padding: const EdgeInsets.fromLTRB(20, 15, 20, 15),
      decoration: BoxDecoration(
        color: CustomColors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(
          color: CustomColors.border,
        ),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Sắp đến hạn',
            style: TextStyle(
              fontSize: 18,
              color: CustomColors.primaryText,
              fontFamily: FontStyleTextStrings.medium,
            ),
          ),
          const SizedBox(height: 10),
          const Text(
            'Không có bài tập nào sắp đến hạn',
            style: TextStyle(
              fontSize: 16,
              color: CustomColors.secondaryText,
              fontFamily: FontStyleTextStrings.regular,
            ),
          ),
          const Spacer(),
          Row(
            children: [
              const Spacer(),
              TextButton(
                onPressed: () {},
                child: const Text(
                  "Xem tất cả",
                  style: TextStyle(
                    fontSize: 18,
                    color: CustomColors.primary,
                    fontFamily: FontStyleTextStrings.medium,
                  ),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _postNewPost() {
    return Container(
      padding: const EdgeInsets.fromLTRB(25, 20, 25, 20),
      decoration: BoxDecoration(
        color: CustomColors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(
          color: CustomColors.border,
        ),
        boxShadow: const [
          BoxShadow(
            color: CustomColors.primaryText,
            blurRadius: 2,
            offset: Offset(0, 1),
          ),
        ],
      ),
      child: Row(
        children: [
          Row(
            children: [
              Container(
                height: 54,
                width: 54,
                decoration: const BoxDecoration(
                  color: Colors.transparent,
                  shape: BoxShape.circle,
                ),
                clipBehavior: Clip.antiAlias,
                child: Image.network(
                  "https://van191024.xyz/upload/avt/default.png",
                  fit: BoxFit.cover,
                ),
              ),
              const SizedBox(width: 10),
              const Text(
                "Thông báo nội dung nào đó cho lớp học của bạn",
                style: TextStyle(
                  fontSize: 16,
                  color: CustomColors.primary,
                  fontFamily: FontStyleTextStrings.regular,
                ),
              ),
            ],
          ),
          const Spacer(),
          IconButton(
            onPressed: () {},
            icon: const Icon(
              Icons.send_rounded,
              color: CustomColors.primary,
            ),
          ),
        ],
      ),
    );
  }
}
